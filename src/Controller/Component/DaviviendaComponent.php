<?php

namespace App\Controller\Component;

use App\Model\Entity\AdjustedObligation;
use App\Model\Entity\Condition;
use App\Model\Entity\Obligation;
use App\Model\Table\ConditionsTable;
use App\Model\Table\RolesTable;
use App\Model\Table\TypeObligationsTable;
use App\Model\Table\ObligationsTable;
use App\Model\Table\TypeRejectedTable;
use App\Model\Table\TypesConditionsTable;
use App\Utility\Pdf;
use Cake\Cache\Cache;
use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\Filesystem\File;
use Cake\Log\Log;
use Cake\Mailer\Email;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;

/**
 * Email Davivienda
 * @property \App\Controller\Component\EmailComponent $Email
 *
 */
class DaviviendaComponent extends Component
{


    /**
     * @var array
     */
    public $obligations = [];
    var $parameters = [];
    var $payment_capacity;
    var $payment_capacity_customer;
    var $initial_payment_punished = 0;
    var $selected = [];
    var $pago_total_castigada = false;
    var $pago_total_vehiculo = false;
    public $components = ['Auth', 'Email'];
    var $data = [];
    var $evaluarFactor = false;
    var $carteraMixta = false;
    var $score = 0;
    var $tdc = false;
    var $cxr = false;
    var $cxf = false;
    var $hip = false;
    var $veh = false;

    var $x1 = 0;
    var $x2 = 0;
    var $x3 = 0;
    var $x4 = 0;
    var $x5 = 0;

    var $y1 = 0;
    var $y2 = 0;
    var $y3 = 0;
    var $y4 = 0;
    var $y5 = 0;

    var $saldoTotalGarantia = 0;
    var $saldoTotalConsumo = 0;
    var $normalizarConsumo = false;
    var $carteraCastigadaHp = false;
    var $oferta_hp_catigada = 0;
    var $customer = null;
    var $oferta = null;
    var $otrasCuotas = 0;

    var $novelity = false;

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {

        $settings = Cache::read('settings', 'long');
        $this->parameters = $settings;
        $this->customer = Cache::read($this->Auth->user('session_id') . 'customer');
    }



    /**
     * @param bool $comite
     * @return array
     */
    public function define_strategy($comite = false)
    {

        if ($comite || $this->Auth->user('role_id') == RolesTable::Coordinador) {
            $techoCoordinador = $this->parameters['Parametros']['factor_1'] / 100;
            $this->parameters['Parametros']['factor_1'] = $this->parameters['Parametros']['piso_zona_gris'];
        }

        /** @var  $customer Customer */
        $customer = $this->customer;
        $risk = $customer->risk;

        /*if ($risk == 'MINIMO' || $risk == 'BAJO') {
            $this->parameters['Parametros']['factor_1'] = $this->parameters['Parametros']['piso_zona_gris'];
        }*/
 
            $data = $this->calculoValores();
            $this->data = $data;

        if ($this->score > 80) {
            $this->parameters['Parametros']['factor_1'] = $this->parameters['Parametros']['piso_zona_gris'];
        }

        $mensaje1 = __('Su caso debe ser evaluado por un comité.');
        $mensaje2 = __('Lo sentimos, no tenemos ninguna oferta que se ajuste a su capacidad de pago.');
        $mensaje3 = __('Esta negociación se encuentra en zona gris.');
        $mensaje4 = __('Lo sentimos no tenemos ninguna oferta al menos se debe seleccionar un producto de consumo.');

        $continue = false;

        $piso = ($this->parameters['Parametros']['piso_zona_gris'] / 100);
        $techo = ($this->parameters['Parametros']['factor_1'] / 100);
        $mensaje = '';

        $oferta = false;
        $factor = $this->factor();
        $factorM = $this->factorM();

        $capacidad = $this->payment_capacity;
        
        if($this->carteraMixta){
            $carteraMixta = $this->carteraMixta($comite);
            
            if($carteraMixta == 1){
                $oferta = true;
                $comite = false;
            }elseif ($carteraMixta == 2){
                $mensaje = $mensaje1;
                $comite = true;
                $oferta = false;
            }elseif ($carteraMixta == 4){
                $mensaje = $mensaje4;
                $comite = false;
                $oferta = false;
            }elseif ($carteraMixta == 3){
                $mensaje = $mensaje2;
                if (!$this->novelity) {
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Mixta no negociable');
                }
                $comite = false;
                $oferta = false;
            }

        }elseif (($this->veh || $this->hip) && $this->payment_capacity > $this->x4) {
            if($this->normalize_restructure_end()){
                $oferta = true;
            }
        }elseif (($factorM >= 1  || $factorM == 0) && $this->payment_capacity > 0 ) {
            if($this->redistribute_restructure()){
                $oferta = true;
            }
        }else {
            if ($this->tdc || $this->cxr || $this->cxf) {
                $this->evaluarFactor = true;
            }
            if ($factor >= $techo && (!$this->hip && !$this->veh)) {
                if($this->normalize()){
                    $oferta = true;
                }
            } else {

                 if (!$this->tdc && !$this->cxr && !$this->cxf) {
                     $a = 0;
                 } else {
                     $a = (($this->x1 + $this->x2) * ($this->parameters['Parametros']['factor_1'] / 100));
                     $a = (($a / 1000) + 1) * 1000;
                 }

                $b = $this->payment_capacity - $a;
                $rNormalizacion = $a;

                $this->payment_capacity -= $rNormalizacion;
                $validacion = $this->y4;
                $capa = $this->payment_capacity;

                $a1= $this->x1;
                $a2 = $this->x2;
                $factor = $this->parameters['Parametros']['factor_1'];

                if ($this->y4 <= $b) {
                    if( $this->normalize_restructure($a)){
                        $oferta = true;
                    }
                } else {
                    $oferta = false;
                    $this->addNovelty(TypeRejectedTable::TASAS, 'Y4 > (Capacidad de Pago - Factor 1) = ' . $this->y4 . ' > (' . $this->payment_capacity . ' - ' .  $a . ')');
                }
            }
        }
        if ($this->evaluarFactor) {
            if ($this->Auth->user('role_id') == RolesTable::Coordinador) {
                if (($factor < $techoCoordinador && $factor > $piso) && $oferta) {
                    $mensaje = $mensaje3;
                }
            } elseif ($factor < $techo && $factor > $piso && $this->Auth->user('role_id') == RolesTable::Asesor) {
                
                /*if ($risk == 'ALTO') {
                    $mensaje = $mensaje2;
                    $oferta = false;
                } elseif ($risk == 'MEDIO') {

                    $mensaje = $mensaje1;
                    $comite = true;
                    $oferta = false;
                }

                if ($this->score < 65) {
                    $mensaje = $mensaje2;
                    $oferta = false;
                } elseif ($this->score >= 65 && $this->score <= 85) {
                    $mensaje = $mensaje1;
                    $comite = true;
                    $oferta = false;
                }*/

                $condiciones = Cache::read('conditions','long');
                $condicionesSIF = $condiciones[TypesConditionsTable::ZONAGRISISF];
                $resultado = $this->getValorCondicion($condicionesSIF, $this->score);

                if ($resultado == 'Rechazar') {
                    $this->addNovelty(TypeRejectedTable::ZONAGRIS, 'Score: ' . $this->score); 
                    $mensaje = $mensaje2;
                    $oferta = false;
                } elseif ($resultado == 'Comite') {
                    $mensaje = $mensaje1;
                    $comite = true;
                    $oferta = false;
                }

            } elseif ($factor < $piso) {
                $mensaje = $mensaje2;
                $oferta = false;
                $this->addNovelty(TypeRejectedTable::TASAS, 'Factor < Piso = ' . $factor . ' < ' . $piso);
            }

        }

        if(!$oferta && $this->otrasAlternativas()){
            $oferta = true;
            $comite = false;
            $mensaje = '';
        }

        if ($oferta) {
            
            Cache::write($this->Auth->user('session_id') . 'obligaciones', $this->obligations);
            $result = [
                'success' => true,
                'data' => [
                    #'obligaciones' => $this->obligations
                ],
                'message' => $mensaje,
                'comite' => $comite,
            ];
        } else {
            if (empty($mensaje)) {
                $mensaje = $mensaje2;
            }
            if (!$this->novelity) {
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'No hay oferta');
            }

            $result = [
                'success' => false,
                'data' => [],
                'message' => $mensaje,
                'comite' => $comite,
            ];
        }

        return $result;
    }

    /**
     * @return bool
     */
    public function redistribute_restructure()
    {
        $totalCuotas = $this->x1 + $this->x2 + $this->x4;
        $capacidadPago = $this->payment_capacity;
        $rNormalizacion = 0;
        $masAlternativas = false;
        $posibleRecalculo = 0;
        $posibleRecalculoID = 0;
        $reCalculo = true;
        $unificacionSobregiro = false;
        $noNegocible = 0;
        $negociables = 0;
        
        $this->obligations = (object)$this->order(1);
        foreach ($this->obligations as $key => $obligation) {
            $tipo = $obligation->type_obligation_id;
            $tasaMensual = pow((1 + ($obligation->rate / 100)), (1 / 12)) - 1;
            
            if ($obligation->restriccionJuridica) {
                $obligation->estrategia = 5;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
                $noNegocible += 1;
            }
            elseif (!in_array($obligation->id, $this->selected) || $obligation->sin_cambio) 
            {
                $obligation->estrategia = 4;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
                $noNegocible += 1;
            }
            elseif (in_array($tipo, [TypeObligationsTable::SOB]) && $unificacionSobregiro)
            {
                $obligation->normalizar = 1;
                $obligation->estrategia = 1;
                $obligation->nuevoPlazo = 0;
                $obligation->nuevaCuota = 0;
                $this->evaluarFactor = true;
                $capacidadPago -= $obligation->cuotaMinima;
                $rNormalizacion += $obligation->cuotaMinima;
                $reCalculo = false;
                $negociables += 1;
            }
            elseif(in_array($tipo, [TypeObligationsTable::SOB]) && !$unificacionSobregiro)
            {
                $obligation->estrategia = 4;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
                $noNegocible += 1;
            }
            elseif($obligation->type_obligation_id == TypeObligationsTable::VEH && $this->pago_total_vehiculo){
                $obligation->negociable = 1;
                $obligation->estrategia = 15;
                $obligation->normalizar = 0;
                $obligation->nuevoPlazo = 0;
                $obligation->nuevaCuota = 0;
                $masAlternativas = true;
                $reCalculo = false;
                $negociables += 1;
            }
            elseif ($obligation->restructuring == 1 && $obligation->esHIPVEH()) {
                $obligation->estrategia = 17;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
                $obligation->nuevaCuota = $obligation->fee;
                $obligation->nuevoPlazo = 0;
                $capacidadPago -= $obligation->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligation->obligation . ', ID REESTRUCTURADO: ' . $obligation->restructuring); 
            }
            elseif ($obligation->id_normalizacion == 1 && $tipo == TypeObligationsTable::CXF) {
                $obligation->estrategia = 16;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
                $obligation->nuevaCuota = $obligation->fee;
                $obligation->nuevoPlazo = 0;
                $capacidadPago -= $obligation->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligation->obligation . ', ID Normalización: ' . $obligation->id_normalizacion); 
            }elseif($obligation->id_normalizacion == 1 && $obligation->retrenched_policy == 1 && $obligation->esConsumo() ){
                $obligation->estrategia = 16;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
                $obligation->nuevaCuota = $obligation->fee;
                $obligation->nuevoPlazo = 0;
                $capacidadPago -= $obligation->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligation->obligation . ', ID Normalización: ' . $obligation->id_normalizacion); 
            }elseif($obligation->id_normalizacion == 1 && $this->normalizarConsumo && $obligation->esConsumo() ){
                $obligation->estrategia = 16;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
                $obligation->nuevaCuota = $obligation->fee;
                $obligation->nuevoPlazo = 0;
                $capacidadPago -= $obligation->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligation->obligation . ', ID Normalización: ' . $obligation->id_normalizacion); 
            }elseif ((($this->normalizarConsumo || $obligation->retrenched_policy == 1 || $tipo == TypeObligationsTable::CXF) && $obligation->esConsumo())) {
                $obligation->normalizar = 1;
                $obligation->estrategia = 1;
                $obligation->nuevoPlazo = 0;
                $obligation->nuevaCuota = 0;
                $this->evaluarFactor = true;
                $capacidadPago -= $obligation->cuotaMinima;
                $rNormalizacion += $obligation->cuotaMinima;
                $reCalculo = false;
                $negociables += 1;
                if(!$unificacionSobregiro ){
                    $unificacionSobregiro = true;
                }
            }
             else {
                $cuotaHoy = $obligation->fee;
                $tasaMensual = pow((1 + ($obligation->rate / 100)), (1 / 12)) - 1;
                
                if($tipo == TypeObligationsTable::CXR){
                    if(is_null($obligation->maxRediferido)){
                        $nuevoPlazo = $obligation->plazo_inicial;
                    }else{
                        $nuevoPlazo = $obligation->maxRediferido;
                    }
                    $cuota = $this->pmt($tasaMensual, $nuevoPlazo, $obligation->total_debt, $tipo, $obligation->currency);
                    $obligation->negociable = 1;
                    $obligation->nuevoPlazo = $nuevoPlazo;
                    $obligation->nuevaCuota = $cuota+$obligation->insurance;
                    $masAlternativas = true;

                }elseif ($capacidadPago < $totalCuotas) {
                    $cuotaSinSeguro = $capacidadPago - $totalCuotas + $cuotaHoy - $obligation->insurance;
                    if ($capacidadPago > ($totalCuotas - $cuotaHoy + $obligation->cuotaMinima)) {
                        
                        $cuota = $capacidadPago - $totalCuotas + $cuotaHoy;
                        $cuotaSinSeguro = $cuota - $obligation->insurance;
                        $nuevoPlazo = $this->nuevoPlazo($obligation, $tasaMensual, $cuotaSinSeguro, 1);
                        $cuota = $this->pmt($tasaMensual, $nuevoPlazo, $obligation->total_debt, $tipo, $obligation->currency);
                        $obligation->negociable = 1;
                        $obligation->nuevoPlazo = $nuevoPlazo;
                        $obligation->nuevaCuota = $cuota+$obligation->insurance;

                    } else {
                        if(is_null($obligation->maxRediferido) || $tipo == TypeObligationsTable::HIP || $tipo == TypeObligationsTable::VEH || $tipo == TypeObligationsTable::SOB ){
                            $nuevoPlazo = $obligation->type_obligation->term;
                        }else{
                            $nuevoPlazo = $obligation->maxRediferido;
                        }
                        $cuota = $obligation->cuotaMinima;
                        $obligation->negociable = 1;
                        $obligation->nuevoPlazo = $nuevoPlazo;
                        $obligation->nuevaCuota = $cuota;
                        $posibleRecalculo++;
                        $posibleRecalculoID = $key;
                        
                    }
                } else {
                    $cuota = $capacidadPago - $totalCuotas + $cuotaHoy;
                    $cuotaSinSeguro = $cuota - $obligation->insurance;
                    $plazoCalculado = $this->nuevoPlazo($obligation, $tasaMensual, $cuotaSinSeguro, 1);
                    if(is_null($obligation->maxRediferido) || ($obligation->maxRediferido > $plazoCalculado) || $tipo == TypeObligationsTable::HIP || $tipo == TypeObligationsTable::VEH || $tipo == TypeObligationsTable::SOB){
                        $nuevoPlazo = $this->nuevoPlazo($obligation, $tasaMensual, $cuotaSinSeguro, 1);
                    }else{
                        $nuevoPlazo = $obligation->maxRediferido;
                    }
                    if($nuevoPlazo == false){
                        return false;
                    }
                    $obligation->negociable = 1;
                    $obligation->nuevoPlazo = $nuevoPlazo;
                    $cuota = $this->pmt($tasaMensual, $nuevoPlazo, $obligation->total_debt, $tipo, $obligation->currency);
                    $obligation->nuevaCuota = $cuota+$obligation->insurance;
                }

                if($nuevoPlazo == false ){
                    return false;
                }

                if (in_array($tipo, [TypeObligationsTable::TDC, TypeObligationsTable::CXR])) {
                    $obligation->estrategia = 3;
                    $negociables += 1;
                } else {
                    $obligation->estrategia = 2;
                    $negociables += 1;
                }
                $totalCuotas = $totalCuotas - $cuotaHoy; //+ $obligation->cuotaMinima;
                $capacidadPago -= $obligation->nuevaCuota;
                
            }

            if ($obligation->estrategia != 4){
                $masAlternativas = true;
            }
        }
        if ($capacidadPago> 0 && $posibleRecalculo >= 1) {
            foreach ($this->obligations as $key => $obligation) {
                if ($key == $posibleRecalculoID) {
                    $tipo = $obligation->type_obligation_id;
                    $tasaMensual = pow((1 + ($obligation->rate / 100)), (1 / 12)) - 1;
                    $nuevaCuota = $obligation->nuevaCuota + $capacidadPago;
                    $nuevoPlazo = $this->nuevoPlazo($obligation, $tasaMensual, $nuevaCuota - $obligation->insurance, 1);
                    $cuota = $this->pmt($tasaMensual, $nuevoPlazo, $obligation->total_debt, $tipo, $obligation->currency);
                    if ($nuevoPlazo > 1) {
                        $obligation->nuevaCuota = $cuota+$obligation->insurance;
                        $obligation->nuevoPlazo = $nuevoPlazo;
                    }
                }
            }
        }
        $capacidadPago += $rNormalizacion;
        if(($capacidadPago < 0 && $rNormalizacion > 0) || !$masAlternativas) {
            $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'La capacidad de pago luego de negociar es menor a 0 ' . $capacidadPago. '.'); 
            return false;
        }elseif ($noNegocible >=1 && $negociables==0){
            return false;
        }else{
            Cache::write($this->Auth->user('session_id') . 'capacidadPago', $capacidadPago);
            return true;
        }

    }

    /**
     * @return bool
     */
    public function normalize()
    {
        $saldoNormalizar = 0;
        $capacidadPago = $this->payment_capacity;
        $cuotasActuales = $this->y1 + $this->x2;
        $unificacionSobregiro = false;
        $noNegocible = 0;
        $negociables = 0;

        $this->obligations = (object)$this->order(2);

        /** @var  $obligacion Obligation */
        foreach ($this->obligations as $obligacion) {

            if ($obligacion->total_debt == 0) {
                $obligacion->total_debt = 1;
            }

            if (!$obligacion->restriccion && in_array($obligacion->id, $this->selected)) {
                $tipo = $obligacion->type_obligation_id;
                $tasaMensual = pow((1 + ($obligacion->rate / 100)), (1 / 12)) - 1;
                if (in_array($tipo, [TypeObligationsTable::SOB]) && $unificacionSobregiro){
                    $obligacion->estrategia = 1;
                    $obligacion->normalizar = 1;
                    $obligacion->nuevoPlazo = 0;
                    $obligacion->nuevaCuota = 0;
                    $saldoNormalizar += $obligacion->total_debt;
                    $this->evaluarFactor = true;
                    $negociables += 1;
                }
                elseif(in_array($tipo, [TypeObligationsTable::SOB]) && !$unificacionSobregiro)
                {
                    $obligacion->estrategia = 4;
                    $obligacion->normalizar = 0;
                    $obligacion->negociable = 0;
                    $obligacion->nuevaCuota = $obligacion->fee;
                    $capacidadPago=$capacidadPago-$obligacion->fee;
                    $noNegocible += 1;

                }elseif ($obligacion->restructuring == 1 && (in_array($tipo,[TypeObligationsTable::VEH,TypeObligationsTable::HIP])) ) 
                {
                    $obligacion->estrategia = 17;
                    $obligacion->negociable = 0;
                    $obligacion->nuevaCuota = $obligacion->fee;
                    $capacidadPago = $capacidadPago - $obligacion->fee;
                    $noNegocible += 1;
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID REESTRUCTURADO: ' . $obligacion->restructuring);       
                }
                elseif ($obligacion->id_normalizacion == 1 && $tipo == TypeObligationsTable::CXF) {
                    $obligacion->estrategia = 16;
                    $obligacion->normalizar = 0;
                    $obligacion->negociable = 0;
                    $obligacion->nuevaCuota = $obligacion->fee;
                    $obligacion->nuevoPlazo = 0;
                    $capacidadPago -= $obligacion->fee;
                    $noNegocible += 1;
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
                }elseif($obligacion->id_normalizacion == 1 && $obligacion->esConsumo() ){
                    $obligacion->estrategia = 16;
                    $obligacion->normalizar = 0;
                    $obligacion->negociable = 0;
                    $obligacion->nuevaCuota = $obligacion->fee;
                    $obligacion->nuevoPlazo = 0;
                    $capacidadPago -= $obligacion->fee;
                    $noNegocible += 1;
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
                }elseif($obligacion->id_normalizacion == 1 && $this->normalizarConsumo && $obligacion->esConsumo() ){
                    $obligacion->estrategia = 16;
                    $obligacion->normalizar = 0;
                    $obligacion->negociable = 0;
                    $obligacion->nuevaCuota = $obligacion->fee;
                    $obligacion->nuevoPlazo = 0;
                    $capacidadPago -= $obligacion->fee;
                    $noNegocible += 1;
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
                }elseif ($obligacion->marcada == 1 && !in_array($tipo, [TypeObligationsTable::HIP, TypeObligationsTable::VEH])) {
                    $obligacion->negociable = 1;
                    $obligacion->estrategia = 1;
                    $obligacion->normalizar = 1;
                    $obligacion->nuevoPlazo = 0;
                    $obligacion->nuevaCuota = 0;
                    $saldoNormalizar += $obligacion->total_debt;
                    $this->evaluarFactor = true;
                    $negociables += 1;
                    if(!$unificacionSobregiro ){
                        $unificacionSobregiro = true;
                    }
                } elseif ($capacidadPago < ($cuotasActuales * $this->parameters['Parametros']['factor_1']) / 100 AND
                    ($obligacion->restructuring == 0 && !in_array($tipo, [TypeObligationsTable::HIP, TypeObligationsTable::VEH]))) {
                    $capacidadPago += $obligacion->fee;
                    $cuotasActuales += $obligacion->fee;
                    $obligacion->estrategia = 1;
                    $obligacion->normalizar = 1;
                    $obligacion->nuevoPlazo = 0;
                    $obligacion->nuevaCuota = 0;
                    $saldoNormalizar += $obligacion->total_debt;
                    $this->evaluarFactor = true;
                    $negociables += 1;
                    if(!$unificacionSobregiro ){
                        $unificacionSobregiro = true;
                    }
                } elseif ($obligacion->restructuring == 0 && $tipo != TypeObligationsTable::SOB) {
                    $cuotaSinSeguro = $obligacion->fee - $obligacion->insurance;
                    $cuota = $obligacion->fee;

                    if (in_array($tipo, [TypeObligationsTable::TDC, TypeObligationsTable::CXR]) || $tasaMensual == 0) {
                        $nuevoPlazo = ceil(1 / (($cuotaSinSeguro / $obligacion->total_debt) - $tasaMensual));
                    } else {
                        $log1 = 1 - (($obligacion->total_debt * $tasaMensual) / $cuotaSinSeguro);
                        $log12 = 1 + $tasaMensual;
                        $nuevoPlazo = ceil((log($log1) * -1) / log($log12));
                    }

                    if ($nuevoPlazo > $obligacion->type_obligation->term) {
                        if ($tipo == TypeObligationsTable::HIP && $obligacion->currency == 'UVR') {
                            $nuevoPlazo = 360;
                        } else {
                            $nuevoPlazo = $obligacion->type_obligation->term;
                        }
                    }


                    $obligacion->nuevoPlazo = $nuevoPlazo;
                    $obligacion->estrategia = 2;
                    $obligacion->nuevaCuota = $cuota;
                    $negociables += 1;
                } elseif ($tipo == TypeObligationsTable::HIP && $obligacion->restructuring == 1) {
                    $obligacion->estrategia = 3;
                    $negociables += 1;
                }
            } else {
                if ($obligacion->restriccionJuridica) {
                    $obligacion->estrategia = 5;
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', Restricción Juridica: Si'); 
                } else {
                    $obligacion->estrategia = 4;
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', Restricción: ' . $obligacion->restriccion); 
                }
                
                $noNegocible += 1;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
            }
        }

        if($saldoNormalizar == 0 || $capacidadPago <= 0){
            return false;
        }elseif ($noNegocible >=1 && $negociables==0){
            return false;
        }else{
            Cache::write($this->Auth->user('session_id') . 'capacidadPago', $capacidadPago);
            return true;
        }

    }

    /**
     * @return bool
     */
    public function normalize_restructure($a)
    {
        $capacidadPago = $this->payment_capacity;
        $this->obligations = (object)$this->order(3);
        $cuotasPendientes = $this->x4;
        $normalizar = false;
        $masAlternativas = false;
        $unificacionSobregiro = false;
        $noNegocible = 0;
        $negociables = 0;

        /** @var  $obligacion Obligation */
        foreach ($this->obligations as $obligacion) {
            if ($obligacion->total_debt == 0) {
                $obligacion->total_debt = 1;
            }

            $tipo = $obligacion->type_obligation_id;
            $tasaMensual = pow((1 + ($obligacion->rate / 100)), (1 / 12)) - 1;


            if ($obligacion->restriccion) {
                $obligacion->estrategia = 5;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $noNegocible += 1;
            } elseif (!in_array($obligacion->id, $this->selected) || $obligacion->sin_cambio) {
                $obligacion->estrategia = 4;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $capacidadPago = $capacidadPago - $obligacion->fee;
                $noNegocible += 1;
            }
            elseif (in_array($tipo, [TypeObligationsTable::SOB]) && $unificacionSobregiro)
            {
                $obligacion->normalizar = 1;
                $obligacion->estrategia = 1;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $this->evaluarFactor = true;
                $capacidadPago -= $obligacion->cuotaMinima;
                $rNormalizacion += $obligacion->cuotaMinima;
                $reCalculo = false;
                $negociables += 1;
            }
            elseif(in_array($tipo, [TypeObligationsTable::SOB]) && !$unificacionSobregiro)
            {
                $obligacion->estrategia = 4;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $capacidadPago = $capacidadPago - $obligacion->fee;
                $noNegocible += 1;
            }
            elseif($obligacion->type_obligation_id == TypeObligationsTable::VEH && $this->pago_total_vehiculo){
                $obligacion->negociable = 1;
                $obligacion->estrategia = 15;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $masAlternativas = true;
                $negociables += 1;
            }
            elseif ($obligacion->id_normalizacion == 1 && $tipo == TypeObligationsTable::CXF) {
                $obligacion->estrategia = 16;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $obligacion->nuevoPlazo = 0;
                $capacidadPago -= $obligacion->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
            }elseif($obligacion->id_normalizacion == 1 && $obligacion->retrenched_policy == 1 && $obligacion->esConsumo() ){
                $obligacion->estrategia = 16;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $obligacion->nuevoPlazo = 0;
                $capacidadPago -= $obligacion->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
            }elseif($obligacion->id_normalizacion == 1 && $this->normalizarConsumo && $obligacion->esConsumo() ){
                $obligacion->estrategia = 16;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $obligacion->nuevoPlazo = 0;
                $capacidadPago -= $obligacion->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
            }elseif ($obligacion->restructuring == 1 && (in_array($tipo,[TypeObligationsTable::VEH,TypeObligationsTable::HIP])) ) {
                $obligacion->estrategia = 17;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $capacidadPago = $capacidadPago - $obligacion->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->id . ', ID REESTRUCTURADO: ' . $obligacion->restructuring); 
            }elseif ($obligacion->retrenched_policy == 1 && $obligacion->esConsumo()) {
                $obligacion->normalizar = 1;
                $obligacion->estrategia = 1;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $normalizar = true;
                $masAlternativas = true;
                $negociables += 1;
                if(!$unificacionSobregiro ){
                    $unificacionSobregiro = true;
                }
            } elseif (in_array($tipo, [TypeObligationsTable::TDC, TypeObligationsTable::CXR, TypeObligationsTable::CXF, TypeObligationsTable::SOB])) {
                $obligacion->normalizar = 1;
                $obligacion->estrategia = 1;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $this->evaluarFactor = true;
                $normalizar = true;
                $masAlternativas = true;
                $negociables += 1;
                if(!$unificacionSobregiro ){
                    $unificacionSobregiro = true;
                }
            } else {

                if ($capacidadPago < $cuotasPendientes) {

                    $cuota = $capacidadPago - ($cuotasPendientes - $obligacion->fee);

                    if ($capacidadPago > ($cuotasPendientes - $obligacion->fee + $obligacion->cuotaMinima)) {
                        $cuotaSinSeguro = ($capacidadPago - ($cuotasPendientes - $obligacion->fee)) - $obligacion->insurance;
                        $nuevoPlazo = $this->nuevoPlazo($obligacion, $tasaMensual, $cuotaSinSeguro, 4);
                        if($nuevoPlazo == false){
                            return false;
                        }
                    } else {
                        $cuotasPendientes = $cuotasPendientes - $obligacion->fee + $obligacion->cuotaMinima;
                        if ($tipo == TypeObligationsTable::HIP && $obligacion->currency == 'UVR') {
                            $nuevoPlazo = 360;
                        } else {
                            $nuevoPlazo = $obligacion->type_obligation->term;
                        }
                    }

                } else {
                    if ($obligacion->restructuring == 0) {
                        $cuotaSinSeguro = $obligacion->fee - $obligacion->insurance;
                        $cuota = $obligacion->cuotaMinima;
                        $nuevoPlazo = $this->nuevoPlazo($obligacion, $tasaMensual, $cuotaSinSeguro, 4);
                    } else {
                        $cuotaSinSeguro = $obligacion->fee - $obligacion->insurance;
                        $cuota = $obligacion->cuotaMinima;
                        $nuevoPlazo = $this->nuevoPlazo($obligacion, $tasaMensual, $cuotaSinSeguro, 4);
                    }
                }
                if($nuevoPlazo == false){
                    return false;
                }
                $masAlternativas = true;
                $cuota = $this->pmt($tasaMensual, $nuevoPlazo, $obligacion->total_debt, $tipo, $obligacion->currency);
                $obligacion->estrategia = 2;
                $obligacion->nuevoPlazo = $nuevoPlazo;
                $obligacion->nuevaCuota = $cuota+$obligacion->insurance;
                $obligacion->negociable = 1;
                $capacidadPago = $capacidadPago - $obligacion->nuevaCuota;
                $negociables += 1;
            }

        }

        if(($capacidadPago <= 0 && $normalizar) || !$masAlternativas){
            if ($capacidadPago <= 0 && $normalizar) {
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Capaciidad de Pago: ' . $capacidadPago ); 
            }

            return false;
        }elseif ($noNegocible >=1 && $negociables==0){
            return false;
        }else{
            Cache::write($this->Auth->user('session_id') . 'capacidadPago', $a + $capacidadPago);
            return true;
        }


    }

    /**
     * @return bool
     */
    public function normalize_restructure_end()
    {
        $capacidadPago = $this->payment_capacity;
        $saldoTotalConsumo = 0;
        $saldoTotalGarantia = 0;
        $diferencia = 0;
        $noNegocible = 0;
        $negociables = 0;
        $restarConsumo = true;
        $piso = $this->getPiso();
        $masAlternativas = false;
        $unificacionSobregiro = false;
        $sumaCuotaAmpliacion = $this->x4;
        $normalizar = false;
        $saldoConsumoNegociable = $this->saldoTotalConsumo;

        $this->obligations = (object)$this->order(4);
        /** @var  $obligacion Obligation */
        foreach ($this->obligations as $obligacion) {
            
            $tasaMensual = pow((1 + ($obligacion->rate / 100)), (1 / 12)) - 1;
            $tipo = $obligacion->type_obligation_id;

            if ($obligacion->total_debt == 0) {
                $obligacion->total_debt = 1;
            }

            if ($obligacion->restriccion) {
                $obligacion->estrategia = 5;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $noNegocible += 1;
            }elseif (!in_array($obligacion->id, $this->selected) || $obligacion->sin_cambio) {
                $obligacion->estrategia = 4;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $noNegocible += 1;
            }
            elseif (in_array($tipo, [TypeObligationsTable::SOB]) && $unificacionSobregiro){
                $obligacion->normalizar = 1;
                $obligacion->estrategia = 1;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $this->evaluarFactor = true;
                $saldoTotalConsumo += $obligacion->total_debt;
                $normalizar = true;
                $masAlternativas = true;
                $negociables += 1;
            }elseif(in_array($tipo, [TypeObligationsTable::SOB]) && !$unificacionSobregiro){
                $obligacion->estrategia = 4;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $noNegocible += 1;
                $saldoConsumoNegociable -= $obligacion->total_debt;
            }
            elseif($obligacion->type_obligation_id == TypeObligationsTable::VEH && $this->pago_total_vehiculo){
                $obligacion->negociable = 1;
                $obligacion->estrategia = 15;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $masAlternativas = true;
                $negociables += 1;
            }
            elseif ($obligacion->restructuring == 1 && $obligacion->esHIPVEH()) {
                $obligacion->estrategia = 17;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $capacidadPago = $capacidadPago - $obligacion->fee;
                $noNegocible += 1;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID REESTRUCTURADO: ' . $obligacion->restructuring); 
            }elseif ($obligacion->id_normalizacion == 1 && $obligacion->esConsumo()) {
                $obligacion->estrategia = 16;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $obligacion->nuevoPlazo = 0;
                $capacidadPago -= $obligacion->fee;
                $noNegocible += 1;
                $saldoConsumoNegociable -= $obligacion->total_debt;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
            }elseif($obligacion->id_normalizacion == 1 && $this->normalizarConsumo && $obligacion->esConsumo() ){
                $obligacion->estrategia = 16;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = $obligacion->fee;
                $obligacion->nuevoPlazo = 0;
                $capacidadPago -= $obligacion->fee;
                $noNegocible += 1;
                $saldoConsumoNegociable -= $obligacion->total_debt;
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
            }elseif ($obligacion->retrenched_policy == 1 && $obligacion->esConsumo()) {
                $obligacion->normalizar = 1;
                $obligacion->estrategia = 1;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $this->evaluarFactor = true;
                $saldoTotalConsumo += $obligacion->total_debt;
                $normalizar = true;
                $masAlternativas = true;
                $negociables += 1;
                if(!$unificacionSobregiro ){
                    $unificacionSobregiro = true;
                }
            } elseif (in_array($tipo, [TypeObligationsTable::TDC, TypeObligationsTable::CXR, TypeObligationsTable::CXF, TypeObligationsTable::SOB])) {
                $obligacion->normalizar = 1;
                $obligacion->estrategia = 1;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $this->evaluarFactor = true;
                $negociables += 1;
                $saldoTotalConsumo += $obligacion->total_debt;
                if ($obligacion->extend != 'SI') {
                    $normalizar = true;
                }
                $masAlternativas = true;
                if(!$unificacionSobregiro ){
                    $unificacionSobregiro = true;
                }
            } else {
                if($restarConsumo){
                    $cuotaConsumo = $this->pmt($piso/100, $this->parameters['Parametros']['plazo_normalizacion'], $saldoConsumoNegociable, $tipo, $obligacion->currency);
                    $seguroConsumo = $saldoConsumoNegociable * (1 + ($piso/100)) * ($this->parameters['Parametros']['factor_seguro']/100);
                    $cuotaConsumo += $seguroConsumo;
                    $saldo = $capacidadPago - $cuotaConsumo;
                    $restarConsumo = false;
                }
                $cuota1 = $saldo*((($obligacion->fee / $this->x4)*1)-$diferencia);
                $cuotaSinSeguro = $cuota1 - $obligacion->insurance;
                $nuevoPlazo = $this->nuevoPlazo($obligacion, $tasaMensual, $cuotaSinSeguro, 2);
                if($nuevoPlazo == false){
                    return false;
                }
                $cuota = $this->pmt($tasaMensual, $nuevoPlazo, $obligacion->total_debt, $tipo, $obligacion->currency);
                if($cuota > $cuota1){
                    $diferencia = $cuota - $cuota1;
                    $diferencia = (($diferencia / $this->x4)*1);
                }else{
                    $diferencia = 0;
                }
                $masAlternativas = true;
                //$negociables += 1;
                $obligacion->estrategia = 2;
                $obligacion->nuevoPlazo = $nuevoPlazo;
                $obligacion->nuevaCuota = $cuota+$obligacion->insurance;
                $cuotaHip = $this->data['hip_vehi']['cuota_minima'];
                //validar proceso de asignación de nuevo plazo (plazo quemado)
                if ($obligacion->type_obligation_id == TypeObligationsTable::HIP && $obligacion->currency == 'UVR' && $nuevoPlazo = 360 && count((array)$this->obligations) == 1) {
                    $cuotaHip = $cuotaHip+$obligacion->insurance;
                }
                Cache::write($this->Auth->user('session_id').'nuevaCuotaHip',$cuotaHip);
                $obligacion->negociable = 1;
                if ($capacidadPago < $obligacion->nuevaCuota){
                    $noNegocible += 1;
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Capacidad de pago menor a la cuota. Información Obligación ' . $obligacion->id . ', Capacidad de pago: ' . $capacidadPago . ', Cuota proyectada: ' . $obligacion->nuevaCuota); 
                }else{
                    $negociables += 1;
                }

                $capacidadPago = $capacidadPago - $obligacion->nuevaCuota;
 
            }
        }

        if($normalizar) {
            if(!$cuotaConsumo){
                $cuota = $this->pmt($piso/100, $this->parameters['Parametros']['plazo_normalizacion'], $saldoConsumoNegociable, $tipo, $obligacion->currency);
                $seguroConsumo = $saldoConsumoNegociable * (1 + ($piso/100)) * ($this->parameters['Parametros']['factor_seguro']/100);
                $cuotaConsumo = $cuota + $seguroConsumo;
            }
            $normalizacion['data'][] = [
                'cuota' => $cuotaConsumo,
                'tasa' => $piso,
                'tasa_anual' => $this->parameters['Parametros']['piso_normalizacion'],
                'plazo' => $this->parameters['Parametros']['plazo_normalizacion'],
                'rango' => true
            ];

            Cache::write($this->Auth->user('session_id').'normalizacion',$normalizacion);
            $capacidadPago -= $cuotaConsumo;
        }

        //$capacidad -= $cuotaConsumo;

        if(($capacidadPago <= 0 && $normalizar) || !$masAlternativas){
            return false;
        }elseif ($noNegocible >=1 && $negociables==0){
            return false;
        }
        else{
            Cache::write($this->Auth->user('session_id') . 'capacidadPago', $capacidadPago);
            return true;
        }

    }


    public function normalization()
    {
        $timeInicio = microtime(true);
        $normalizacion = [];
        $normalizacion['success'] = true;
        $normalizacion['data'] = [];
        $capacidadPago = $this->payment_capacity;
        $this->obligaciones = $this->obligations;
        $plazo = 0;
        //$plazo = $this->parameters['Parametros']['plazo_normalizacion'];
        $saldoTotal = 0;
        $tir = 0;
        $pagoSugerido = 0;
        $pagoAcumlado = 0;
        $abonoReal = 0;
        /** @var  $obligacion Obligation */
        foreach ($this->obligaciones as $obligacion) {
            if ($obligacion->total_debt == 0) {
                $obligacion->total_debt = 1;
            }

            if ($obligacion->normalizar == 1 && !$obligacion->restriccion) {
                $saldoTotal += $obligacion->total_debt;
                $plazo = $obligacion->maxUnificacion;
                $pagoAcumlado += $obligacion->acomulated_payment;
            }
        }
        if($plazo == 0 || $plazo == null){
            $plazo = $this->parameters['Parametros']['plazo_normalizacion'];
        }

        foreach ($this->obligaciones as $obligacion) {
            if ($obligacion->total_debt == 0) {
                $obligacion->total_debt = 1;
            }
            if ($obligacion->normalizar == 1 && !$obligacion->restriccion) {
                $tir += ((($obligacion->rate / 100) * ($obligacion->total_debt)) / $saldoTotal);
            }
        }

        if ($tir != round($tir, 6)) {
            $tir = round($tir, 6) + 0.000001;
        }

        $tir = round(pow(1 + $tir, (1 / 12)) - 1, 6);

        if ($tir < 0.01) {
            $tir = 0.01;
        }

        $cuotaTotal = $saldoTotal * $tir / (1 - pow((1 + $tir), - $plazo));
        $tasaMensual = $tir;

        if ($cuotaTotal > $capacidadPago) {
            $xInicial = $tir;
            $xMedio = $xInicial - 0.001;
            $vp = $capacidadPago / $xMedio * (1 - (1 / (pow(1 + $xMedio, $plazo))));
            $distancia = $saldoTotal - $vp;

            while ($distancia > 0.001) {
                $fXinicial = $saldoTotal - ($capacidadPago / $xInicial * (1 - (1 / pow(1 + $xInicial, $plazo))));
                $fXmedio = $saldoTotal - ($capacidadPago / $xMedio * (1 - (1 / pow(1 + $xMedio, $plazo))));

                if ($xMedio - $xInicial != 0) {
                    $fraccion = ($xMedio - $xInicial) / ($fXmedio - $fXinicial);
                    $xFinal = $xMedio - ($fraccion * $fXmedio);
                    $xInicial = $xMedio;
                    $xMedio = $xFinal;
                    $vp = ($capacidadPago / $xMedio) * (1 - (1 / (pow(1 + $xMedio, $plazo))));
                    $distancia = $saldoTotal - $vp;
                }

            }
            if ($xMedio < 0) {
                #$xMedio = $xMedio * -1;
            }
            $tasaMensual = round($xMedio, 6);
        }

        $piso = $this->getTasaEfectivaMensual($this->parameters['Parametros']['piso_normalizacion']);
        $techo = $this->getTasaEfectivaMensual($this->parameters['Parametros']['tasa_techo_unificacion']);

        if ($tasaMensual  < $piso) {
            $tasaMensual = $piso;
        }elseif ($tasaMensual > $techo){
            $tasaMensual = $techo;
        }

        $tasa = round($tasaMensual * 100, 2);

        if ($tasaMensual * 100 >= 0 && $tasaMensual * 100 < 0.01) {
            $normalizacion['observacion'] = 'Escalonada';
            $normalizacion['ind_escalonada'] = 1;
            $normalizacion['msg'] = "Oferta de normalización escalonada.";
            $normalizacion['data'][] = [
                'cuota' => $this->thousand_upper($saldoTotal / $this->parameters['Parametros']['plazo_normalizacion']),
                'tasa' => $piso * 100,
                'tasa_anual' => $this->parameters['Parametros']['piso_normalizacion'],
                'plazo' => $this->parameters['Parametros']['plazo_normalizacion'],
                'rango' => true,
                'pago_acumulado' => $pagoAcumlado,
                'abono_real' => $abonoReal
            ];

        } elseif ($tasaMensual * 100 < 0) {
            $normalizacion['success'] = false;
            $normalizacion['msg'] = "No existe una oferta para hacerle al cliente.";
            return $normalizacion;
        } else {
            $f2 = $saldoTotal * (1 + $tasaMensual) * ($this->parameters['Parametros']['factor_seguro']);
            $tasaAnual = round(((pow(1 + $tasaMensual, 12)) - 1) * 100, 2);
            for ($i = 3; $i <= $plazo; $i += 3) {
                $f1 = $saldoTotal * $tasaMensual / (1 - (1 / pow(1 + $tasaMensual, $i)));
                $cuotaTotal = $this->thousand_upper($f1) + $this->thousand_upper($f2);
                $rango = false;
                if ($cuotaTotal >= $capacidadPago && $cuotaTotal <= ($capacidadPago * 1.3)) {
                    $rango = true;
                }
                
                $customer = Cache::read($this->Auth->user('session_id') . 'customer');
                $configuracion = Cache::read('settings', 'long');
                $porcentagePago = $configuracion['Parametros']['porcentaje_pago_sugerido'];
                $pagoSugerido = $cuotaTotal*($porcentagePago/100);
                
                if ($pagoSugerido > $pagoAcumlado){
                    $abonoReal = $pagoSugerido - $pagoAcumlado;
                }else{
                    $abonoReal = 0;

                }
                
                $normalizacion['data'][$i] = [
                    'cuota' => $cuotaTotal,
                    'tasa' => $tasa,
                    'tasa_anual' => $tasaAnual,
                    'plazo' => $i,
                    'rango' => $rango,
                    'pago_acumulado' => $pagoAcumlado,
                    'pago_sugerido' => $pagoSugerido,
                    'abono_real' => $abonoReal
                ];
            }
        }

        krsort($normalizacion['data']);
        $a = [];
        $b = [];
        foreach ($normalizacion['data'] as $oferta) {
            if ($oferta['cuota'] >= $capacidadPago) {
                $a[] = $oferta;
            } elseif ($oferta['cuota'] < $capacidadPago) {
                $b[] = $oferta;
            }
        }
        
        $length = 3;
        if (empty($b)) {
            $length = 6;
        }
        $a = array_slice($a, 0, $length);
        krsort($b);
        $b = array_slice($b, 0, $length);
        krsort($a);

        $normalizacion['data'] = array_merge($a, $b);
        $this->tiempos($timeInicio, microtime(true), 'normalizacion');

        return $normalizacion;

    }

    /**
     * @param $opt
     * @return array
     */
    public function order($opt)
    {
        $orden = [];
        $tdc = [];
        $cxr = [];
        $cxf = [];
        $cxfN = [];
        $veh = [];
        $vehN = [];
        $hip = [];
        $hipN = [];
        $sob = [];
        $otros = [];
        /** @var  $obligacion Obligation */
        foreach ($this->obligations as $obligacion) {
            $tipo = $obligacion->type_obligation_id;
            if ($tipo == TypeObligationsTable::TDC) {
                $tdc[] = $obligacion;
            } elseif ($tipo == TypeObligationsTable::CXR) {
                $cxr[] = $obligacion;
            } elseif ($tipo == TypeObligationsTable::CXF && $obligacion->negociable == 1) {
                $cxf[] = $obligacion;
            } elseif ($tipo == TypeObligationsTable::CXF && $obligacion->negociable == 0) {
                $cxfN[] = $obligacion;
            } elseif ($tipo == TypeObligationsTable::VEH && $obligacion->negociable == 1) {
                $veh[] = $obligacion;
            } elseif ($tipo == TypeObligationsTable::VEH && $obligacion->negociable == 0) {
                $vehN[] = $obligacion;
            } elseif ($tipo == TypeObligationsTable::HIP && $obligacion->negociable == 1) {
                $hip[] = $obligacion;
            } elseif ($tipo == TypeObligationsTable::HIP && $obligacion->negociable == 0) {
                $hipN[] = $obligacion;
            } elseif ($tipo == TypeObligationsTable::SOB) {
                $sob[] = $obligacion;
            } 
            else{
                $otros[] = $obligacion;
            }
        }
        if ($opt == 1) {
            $orden = array_merge($orden, $cxr);
            $orden = array_merge($orden, $tdc);
            $orden = array_merge($orden, $cxf);
            $orden = array_merge($orden, $cxfN);
            $orden = array_merge($orden, $veh);
            $orden = array_merge($orden, $vehN);
            $orden = array_merge($orden, $hip);
            $orden = array_merge($orden, $hipN);
            $orden = array_merge($orden, $otros);
            $orden = array_merge($orden, $sob);
            return $orden;
        } elseif ($opt == 2) {
            $orden = array_merge($orden, $cxfN);
            $orden = array_merge($orden, $tdc);
            $orden = array_merge($orden, $cxr);
            $orden = array_merge($orden, $cxf);
            $orden = array_merge($orden, $veh);
            $orden = array_merge($orden, $vehN);
            $orden = array_merge($orden, $hip);
            $orden = array_merge($orden, $hipN);
            $orden = array_merge($orden, $otros);
            $orden = array_merge($orden, $sob);
            return $orden;
        } elseif ($opt == 3) {
            $orden = array_merge($orden, $veh);
            $orden = array_merge($orden, $vehN);
            $orden = array_merge($orden, $hip);
            $orden = array_merge($orden, $hipN);
            $orden = array_merge($orden, $tdc);
            $orden = array_merge($orden, $cxr);
            $orden = array_merge($orden, $cxf);
            $orden = array_merge($orden, $cxfN);
            $orden = array_merge($orden, $otros);
            $orden = array_merge($orden, $sob);
            return $orden;
        }
        elseif ($opt == 4) {
            $orden = array_merge($orden, $cxfN);
            $orden = array_merge($orden, $tdc);
            $orden = array_merge($orden, $cxr);
            $orden = array_merge($orden, $cxf);
            $orden = array_merge($orden, $sob);
            $orden = array_merge($orden, $veh);
            $orden = array_merge($orden, $vehN);
            $orden = array_merge($orden, $hip);
            $orden = array_merge($orden, $hipN);
            $orden = array_merge($orden, $otros);
            
            return $orden;
        }

    }

    /**
     * @param Obligation $obligacion
     * @return float
     */
    public function calculate_minimum_quota(Obligation $obligacion)
    {

        $tipoObligacion = $obligacion->type_obligation_id;
        $tasaMensual = pow((1 + ($obligacion->rate / 100)), (1 / 12)) - 1;
        $saldoTotal = $obligacion->total_debt;
        $plazoMaximo = $obligacion->type_obligation->term;
        $plazoMaxRediferido = $obligacion->maxRediferido;
        if($tipoObligacion == TypeObligationsTable::TDC){
            $customer = Cache::read($this->Auth->user('session_id') . 'customer');
            if($obligacion->retrenched_policy == 0 && $customer['circular_026'] != 'Reestructuracion'){
                $condiciones = Cache::read('conditions','long');
                $condicionesR = $condiciones[TypesConditionsTable::REDIFERIDO];
                $plazoMaximo = $this->getValorCondicion($condicionesR,$obligacion->days_past_due);
                if($plazoMaximo == null){
                    if(is_null($plazoMaxRediferido)){
                        $plazoMaximo = $obligacion->type_obligation->term;
                    }else {
                        $plazoMaximo = $plazoMaxRediferido;
                    }
                }
            }
        }
        if (in_array($tipoObligacion, [TypeObligationsTable::TDC, TypeObligationsTable::CXR])) {
            if(is_null($plazoMaxRediferido)){
                $plazoMaximo = $obligacion->type_obligation->term;
            }else {
                $plazoMaximo = $plazoMaxRediferido;
            }
            $cuotaMinima = (($saldoTotal / $plazoMaximo) + ($saldoTotal * $tasaMensual)) + $obligacion->insurance;
        } elseif ($tipoObligacion == TypeObligationsTable:: HIP && $obligacion->currency == 'UVR') {
            $uvrRate = ObligationsTable::annualEffectiveRateUvr();
            $uvrValor = $uvrRate['value'];
            
            $uvrSaldoTotal = $obligacion->valor_desembolsado / $uvrValor;
            $saldoUVR = $saldoTotal / $uvrValor;
            $interesUVR = $saldoUVR * $tasaMensual;
            $uvrAmortizacion = $saldoUVR / $plazoMaximo;
            $cuotaUVR = $interesUVR + $uvrAmortizacion;
            $variacionUVR = 1+(6/10);
            $nuevovrUVR = $uvrValor + $variacionUVR;
            $cuotaM = $cuotaUVR * $nuevovrUVR;
            $cuotaMinima = $cuotaM + $obligacion->insurance;

        } elseif (in_array($tipoObligacion, [TypeObligationsTable::VEH, TypeObligationsTable::CXF, TypeObligationsTable::HIP])) {
            if ($tasaMensual > 0) {
                $cuotaMinima = (($saldoTotal * $tasaMensual) / (1 - (1 / pow(1 + $tasaMensual, $plazoMaximo)))) + $obligacion->insurance;
            } elseif ($tasaMensual == 0) {
                $cuotaMinima = ($saldoTotal / $plazoMaximo) + $obligacion->insurance;
            }
        } elseif ($tipoObligacion == TypeObligationsTable::SOB) {
            $cuotaMinima = $obligacion->fee;
        } else {
            $cuotaMinima = $obligacion->fee;
        }
        //return $this->thousand_upper($cuotaMinima);
        return ceil($cuotaMinima);
    }

    public function thousand_upper($valor)
    {
        #if(($valor % 100) == 0){
         #   return $valor;
        #}else{
            return (ceil(ceil($valor) / 1000)) * 1000;
        #}

    }


    public function generate_pdf_normalization($negociacion)
    {
        /** @var  $negociacion AdjustedObligation */

        $pdf = new Pdf();
        $pdf->SetProtection(array('copy', 'print'), $negociacion->identification);
        $pdf->SetMargins(25, 25, 25);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Ln(10);
        $pdf->Cell(15, 0, $negociacion->city . ', ' . date('d') . ' de ' . date('F') . ' ' . date('Y'));
        $pdf->Ln(20);
        $pdf->Cell(0, 0, 'Apreciado cliente,');
        $pdf->Ln(5);
        $pdf->SetFont('');
        $pdf->Cell(0, 0, $negociacion->customer_name);
        $pdf->Ln(15);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50);
        $pdf->Cell(18, 0, 'Asunto: ');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 0, 'Siempre tendrá nuestra orientación financiera. ', 0, 0, 'l');
        $pdf->Ln(10);
        $pdf->MultiCell(0, 5, 'Reciba un cordial saludo de Davivienda. Para nosotros fue grato poder orientarlo y así acordar de manera conjunta una solución ajustada a sus necesidades y estar al día con sus obligaciones con el Banco. Teniendo en cuenta lo negociado con usted, a continuación le presentamos la tabla con los detalles de la situación de sus productos incluidos y no incluidos para el plan de pago de sus obligaciones, al igual que la información sobre la fecha y la oficina Davivienda a la cual usted puede acercarse, presentando la documentación que se relaciona en la presente comunicación:', 0, 'J');

        $pdf->Ln(5);
        $y1 = $pdf->GetY();
        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 9);

        $pdf->SetTextColor(255, 30, 30);

        $pdf->Cell(48);
        $pdf->Cell(56, 0, 'Resumen');
        $pdf->Cell(0, 0, 'Unificación Productos de Consumo');
        $pdf->Ln(3);
        $y = $pdf->GetY();

        $pdf->SetFont('Arial', '', 8);

        $w = array(10, 28, 30, 10, 20);
        $header = ['Tipo', 'Obligación', 'Alternativa', 'Plazo', 'Cuota'];
        $data = [];
        /** @var  $detail AdjustedObligationsDetail */
        foreach ($negociacion->adjusted_obligations_details as $detail) {
            $obligacion = substr($detail->obligation, 0, 3) . '*****' . substr($detail->obligation, -4);

            if ($detail->strategy == 'Unificación de Obligaciones') {
                $cuota = '--';
                $term = '--';
                $cutaNegociacion = '$' . number_format($detail->new_fee, 0, '.', '.');
                $tasaNegociacion = $detail->annual_effective_rate . '%';
                $plazoNegociacion = $detail->months_term;
            } else {
                $cuota = '$' . number_format($detail->new_fee, 0, '.', '.');
                $term = $detail->months_term;
            }
            $data[] = [
                $detail->type_obligation,
                $obligacion,
                $detail->strategy,
                $term,
                $cuota
            ];
        }

        $y2 = $pdf->ImprovedTable($header, $data, $w, 5);
        $y3 = $y2;

        $pdf->SetY($y2);
        $pdf->Ln(3);

        $w = array(30, 12, 12);
        $header = ['Cuota', 'Tasa', 'Plazo'];
        $data = [
            [
                $cutaNegociacion,
                $tasaNegociacion,
                $plazoNegociacion
            ]
        ];
        $y2 = $pdf->ImprovedTable($header, $data, $w, 105, $y);

        $pdf->SetY($y2);

        $pdf->Cell(105);
        $header = [];
        $data = [['Abono Cliente', number_format($negociacion->payment_agreed, 0, '.', '.')], ['Abono Cliente', number_format($negociacion->payment_agreed, 0, '.', '.')],];
        $w = array(25, 25);

        foreach ($data as $row) {
            $pdf->Cell(105);
            $i = 0;
            foreach ($row as $val) {
                if ($i == 0) {
                    $fill = 1;
                    $pdf->SetFillColor(255, 30, 30);
                    $pdf->SetTextColor(242, 232, 232);
                } else {
                    $pdf->SetTextColor(16, 1, 1);
                    $fill = 0;
                }
                $pdf->Cell($w[$i], 6, $val, 1, 0, 'C', $fill);
                $i++;
            }
            $pdf->Ln();
        }

        $pdf->SetY($y2 + 7);
        $pdf->Cell(105);
        $header = [];
        $data = [
            [
                'Abono Cliente', number_format($negociacion->payment_agreed)
            ],
            [
                'Fecha Abono', $negociacion->documentation_date->format('Y-m-d')
            ]
        ];

        $w = array(25, 25);

        foreach ($data as $row) {
            $pdf->Cell(105);
            $i = 0;
            foreach ($row as $val) {
                if ($i == 0) {
                    $fill = 1;
                    $pdf->SetFillColor(255, 30, 30);
                    $pdf->SetTextColor(242, 232, 232);
                } else {
                    $pdf->SetTextColor(16, 1, 1);
                    $fill = 0;
                }
                $pdf->Cell($w[$i], 6, $val, 1, 0, 'C', $fill);
                $i++;
            }
            $pdf->Ln();
        }
        $pdf->Ln(3);

        $y2 = $pdf->GetY();

        if ($y2 > $y3) {
            $pdf->SetY($y2);
        } else {
            $pdf->SetY($y3);
        }

        $pdf->Cell(5);
        $fill = 1;
        $pdf->SetFillColor(255, 30, 30);
        $pdf->SetTextColor(242, 232, 232);
        $pdf->Cell(15, 6, 'Sucursal', 1, 0, 'C', $fill);

        $fill = 0;
        $pdf->SetFillColor('');
        $pdf->SetTextColor('');
        $pdf->CellFitScale(30, 6, $negociacion->office_name, 1, 0, 'C', $fill);

        $pdf->Cell(1);
        $fill = 1;
        $pdf->SetFillColor(255, 30, 30);
        $pdf->SetTextColor(242, 232, 232);
        $pdf->Cell(15, 6, 'Dirección', 1, 0, 'C', $fill);

        $fill = 0;
        $pdf->SetFillColor('');
        $pdf->SetTextColor('');
        $pdf->CellFitScale(30, 6, $negociacion->office_address, 1, 0, 'C', $fill);

        $pdf->Cell(1);
        $fill = 1;
        $pdf->SetFillColor(255, 30, 30);
        $pdf->SetTextColor(242, 232, 232);
        $pdf->CellFitScale(30, 6, 'Fecha Documentación', 1, 0, 'C', $fill);

        $fill = 0;
        $pdf->SetFillColor('');
        $pdf->SetTextColor('');
        $pdf->CellFitScale(20, 6, $negociacion->documentation_date->format('Y-m-d'), 1, 0, 'C', $fill);

        $pdf->Cell(1);
        /*$fill = 1;
        $pdf->SetFillColor(255, 30, 30);
        $pdf->SetTextColor(242, 232, 232);
        $pdf->CellFitScale(10,6,'Hora',1,0,'C',$fill);

        $fill = 0;
        $pdf->SetFillColor('');
        $pdf->SetTextColor('');
        $pdf->CellFitScale(15,6,date('H:i'),1,0,'C',$fill);*/
        $pdf->Ln(8);
        $y2 = $pdf->GetY();

        $pdf->RoundedRect(25, $y1, 165, $y2 - $y1, 2);
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, 'Por lo anterior, lo invitamos a continuar con los pasos indicados por el orientador financiero para llevar a cabo exitosamente este proceso.');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 5, 'Adicionalmente, le recordamos que, mantener al día sus obligaciones, le evita reportes negativos en centrales de información.');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 5, 'A continuación le informamos los documentos que debe presentar según la alternativa de negociación acordada:');
        $pdf->Ln(8);
        $pdf->Cell(10);
        $pdf->Cell(0, 0, '1. Rediferido:');
        $pdf->Ln(5);
        $pdf->Cell(20);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCellBlt(100, 5, '*', 'No requiere documentación.');

        $column_width = 145;
        $sample_text = 'This is bulleted text. The text is indented and the bullet appears at the first line only. This list is built with a single call to MultiCellBltArray().';

        $pdf->Ln(5);
        //Test1
        $test1 = array();
        $test1['bullet'] = '*';
        $test1['margin'] = '';
        $test1['indent'] = 0;
        $test1['spacer'] = 0;
        $test1['text'] = [
            'Documento(s) de identidad.',
            'Para obligaciones de leasing: Soportes del pago de impuestos, administración y servicios los cuales deben estar completamente al día.',
            'Para obligaciones de crédito hipotecario: Certificado de libertad del inmueble con expedición no mayor a 30 días.'
        ];

        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->Cell(0, 0, '2. Ampliación de plazo hipotecario:');
        $pdf->Ln(5);

        $pdf->Cell(20);
        $pdf->MultiCellBltArray($column_width, 6, $test1);

        $pdf->Ln(5);
        //Test1
        $test1 = array();
        $test1['bullet'] = '*';
        $test1['margin'] = '';
        $test1['indent'] = 0;
        $test1['spacer'] = 0;
        $test1['text'] = array();
        $test1['text'] = [
            'Documento(s) de identidad.',
            'Póliza de vehículo vigente',
            'Certificado de libertad no mayor a 30 días ( Solo aplica para reestructuración de créditos judicializados)'
        ];

        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->Cell(0, 0, '3. Ampliación de Plazo Vehículo:');
        $pdf->Ln(5);

        $pdf->Cell(20);
        $pdf->MultiCellBltArray($column_width, 6, $test1);

        $pdf->Ln(5);
        //Test1
        $test1 = array();
        $test1['bullet'] = '*';
        $test1['margin'] = '';
        $test1['indent'] = 0;
        $test1['spacer'] = 0;
        $test1['text'] = array();
        $test1['text'] = [
            'Documento(s) de identidad.',
            'Para Normalización Libranza: Autorización de libranza firmada por el convenio',
            'Para Normalización de Fuerzas Militares: Carta de haberes.'
        ];

        $pdf->Ln(5);
        $pdf->Cell(10);
        $pdf->Cell(0, 0, '4. Unificación de Cupo:');
        $pdf->Ln(5);

        $pdf->Cell(20);
        $pdf->MultiCellBltArray($column_width, 6, $test1);

        $pdf->Ln(5);

        $pdf->MultiCell(0, 5, 'En Davivienda tenemos la mejor intención de seguir colaborando con usted para satisfacer sus expectativas y necesidades a través de nuestros diferentes productos y servicios.');

        $pdf->Ln(15);

        $pdf->Cell(0, 0, 'Atentamente,');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Ln(20);
        $pdf->Cell(0, 0, 'BANCO DAVIVIENDA S.A.');
        $pdf->Ln(15);
        $pdf->Cell(0, 0, 'Términos y Condiciones');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10);
        $pdf->MultiCell(0, 5, 'Acuerdo válido hasta 4 días hábiles después de recibir este correo. Las obligaciones a  rediferir no deben presentar esta novedad en los últimos 4 meses y a todas las transacciones que se realicen les será aplicable la tasa vigente del Banco incluyendo las transacciones que cuenten con una tasa preferencial. No aplica para tarjetas de crédito empresarial, agropecuario, cafetera, sin banda, adicionales, Fondavivienda, marca privada, línea libranza, cuota fija y Zuana. Para acuerdos de Unificación de Productos corresponderá a la tasa de interés acordada entre el Cliente y el Banco. Para obligaciones con convenio de libranza solo será efectiva, previa autorización de la Empresa con quien se tiene suscrito el Convenio.');
        $pdf->Ln(4);
        $pdf->Cell(10);
        $pdf->MultiCell(0, 5, 'Los valores pueden estar sujetos a cambios de acuerdo a la tasa vigente al momento en que se efectúe la operación, salvo acuerdo previo entre el Cliente y el Banco. El monto de la cuota calculada podrá ser mayor, en el evento en que el Cliente efectúe nuevas utilizaciones y/o se carguen cobros administrativos u otros costos.');
        $pdf->Ln(4);
        $pdf->Cell(10);
        $pdf->MultiCell(0, 5, 'Las tarifas y tasas vigentes pueden ser consultadas en www.Davivienda.com. Recuerde que el Banco nunca solicitará por este medio información de acceso ni datos de sus productos (claves de acceso a la página web, números de cuentas ni números de tarjetas de crédito).');
        $pdf->Ln(5);

        $filename = TMP . 'pdf' . DS . $negociacion->id . time() . '.pdf';

        $pdf->Output($filename, 'F');
        return $this->sendEmailPdf($filename, $negociacion->customer_name, $negociacion->customer_email);
    }

    public function generate_pdf($negociacion)
    {
        /** @var  $negociacion AdjustedObligation */

        $pdf = new Pdf();
        $pdf->SetProtection(array('copy', 'print'), $negociacion->identification);
        $pdf->SetMargins(25, 25, 25);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Ln(10);
        $pdf->Cell(15, 0, $negociacion->city . ', ' . date('d') . ' de ' . date('F') . ' ' . date('Y'));
        $pdf->Ln(20);
        $pdf->Cell(0, 0, 'Apreciado cliente,');
        $pdf->Ln(5);
        $pdf->SetFont('');
        $pdf->Cell(0, 0, $negociacion->customer_name);
        $pdf->Ln(15);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(50);
        $pdf->Cell(18, 0, 'Asunto: ');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 0, 'Siempre tendrá nuestra orientación financiera. ', 0, 0, 'l');
        $pdf->Ln(10);
        $pdf->MultiCell(0, 5, 'Reciba un cordial saludo de Davivienda. Para nosotros fue grato poder orientarlo y así acordar de manera conjunta una solución ajustada a sus necesidades y estar al día con sus obligaciones con el Banco. Teniendo en cuenta lo negociado con usted, a continuación le presentamos la tabla con los detalles de la situación de sus productos incluidos y no incluidos para el plan de pago de sus obligaciones, al igual que el pago y fecha de pago acordado', 0, 'J');

        $pdf->Ln(5);
        $y1 = $pdf->GetY();
        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 9);

        $pdf->SetTextColor(255, 30, 30);

        $pdf->Cell(68);
        $pdf->Cell(0, 0, 'Resumen');
        $pdf->Ln(3);
        $y = $pdf->GetY();

        $pdf->SetFont('Arial', '', 8);

        $w = array(10, 28, 30, 10, 20);
        $header = ['Tipo', 'Obligación', 'Alternativa', 'Plazo', 'Cuota'];
        $data = [];
        /** @var  $detail AdjustedObligationsDetail */
        foreach ($negociacion->adjusted_obligations_details as $detail) {
            $obligacion = substr($detail->obligation, 0, 3) . '*****' . substr($detail->obligation, -4);

            if ($detail->strategy == 'Unificación de Obligaciones') {
                $cuota = '--';
                $term = '--';
                $cutaNegociacion = '$' . number_format($detail->new_fee, 0, '.', '.');
                $tasaNegociacion = $detail->annual_effective_rate . '%';
                $plazoNegociacion = $detail->months_term;
            } else {
                $cuota = '$' . number_format($detail->new_fee, 0, '.', '.');
                $term = $detail->months_term;
            }
            $data[] = [
                $detail->type_obligation,
                $obligacion,
                $detail->strategy,
                $term,
                $cuota
            ];
        }

        $y2 = $pdf->ImprovedTable($header, $data, $w, 28);
        $y3 = $y2;


        $pdf->Ln(3);

        $y2 = $pdf->GetY();

        if ($y2 > $y3) {
            $pdf->SetY($y2);
        } else {
            $pdf->SetY($y3);
        }

        $pdf->Cell(28);
        $fill = 1;
        $pdf->SetFillColor(255, 30, 30);
        $pdf->SetTextColor(242, 232, 232);
        $pdf->Cell(25, 6, 'Abono Cliente', 1, 0, 'C', $fill);

        $fill = 0;
        $pdf->SetFillColor('');
        $pdf->SetTextColor('');
        $pdf->CellFitScale(20, 6, '$' . number_format($negociacion->payment_agreed, 0, '.', '.'), 1, 0, 'C', $fill);

        $pdf->Cell(1);
        $fill = 1;
        $pdf->SetFillColor(255, 30, 30);
        $pdf->SetTextColor(242, 232, 232);
        $pdf->CellFitScale(31, 6, 'Fecha Abono', 1, 0, 'C', $fill);

        $fill = 0;
        $pdf->SetFillColor('');
        $pdf->SetTextColor('');
        $pdf->CellFitScale(21, 6, $negociacion->documentation_date->format('Y-m-d'), 1, 0, 'C', $fill);

        $pdf->Cell(1);
        /*$fill = 1;
        $pdf->SetFillColor(255, 30, 30);
        $pdf->SetTextColor(242, 232, 232);
        $pdf->CellFitScale(10,6,'Hora',1,0,'C',$fill);

        $fill = 0;
        $pdf->SetFillColor('');
        $pdf->SetTextColor('');
        $pdf->CellFitScale(15,6,date('H:i'),1,0,'C',$fill);*/
        $pdf->Ln(8);
        $y2 = $pdf->GetY();

        $pdf->RoundedRect(46, $y1, 112, $y2 - $y1, 2);
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 12);
        $pdf->MultiCell(0, 5, 'Por lo anterior, lo invitamos a continuar con los pasos indicados por el orientador financiero para llevar a cabo exitosamente este proceso.');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 5, 'Adicionalmente, le recordamos que, mantener al día sus obligaciones, le evita reportes negativos en centrales de información.');
        $pdf->Ln(3);
        $pdf->MultiCell(0, 5, 'En Davivienda tenemos la mejor intención de seguir colaborando con usted para satisfacer sus expectativas y necesidades a través de nuestros diferentes productos y servicios.');
        $pdf->Ln(15);

        $pdf->Cell(0, 0, 'Atentamente,');

        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Ln(20);
        $pdf->Cell(0, 0, 'BANCO DAVIVIENDA S.A.');
        $pdf->Ln(15);
        $pdf->Cell(0, 0, 'Términos y Condiciones');
        $pdf->Ln(5);
        $pdf->SetFont('Arial', '', 8);
        $pdf->Cell(10);
        $pdf->MultiCell(0, 5, 'Acuerdo válido hasta 4 días hábiles después de recibir este correo. Las obligaciones a  rediferir no deben presentar esta novedad en los últimos 4 meses y a todas las transacciones que se realicen les será aplicable la tasa vigente del Banco incluyendo las transacciones que cuenten con una tasa preferencial. No aplica para tarjetas de crédito empresarial, agropecuario, cafetera, sin banda, adicionales, Fondavivienda, marca privada, línea libranza, cuota fija y Zuana. Para acuerdos de Unificación de Productos corresponderá a la tasa de interés acordada entre el Cliente y el Banco. Para obligaciones con convenio de libranza solo será efectiva, previa autorización de la Empresa con quien se tiene suscrito el Convenio.');
        $pdf->Ln(4);
        $pdf->Cell(10);
        $pdf->MultiCell(0, 5, 'Los valores pueden estar sujetos a cambios de acuerdo a la tasa vigente al momento en que se efectúe la operación, salvo acuerdo previo entre el Cliente y el Banco. El monto de la cuota calculada podrá ser mayor, en el evento en que el Cliente efectúe nuevas utilizaciones y/o se carguen cobros administrativos u otros costos.');
        $pdf->Ln(4);
        $pdf->Cell(10);
        $pdf->MultiCell(0, 5, 'Las tarifas y tasas vigentes pueden ser consultadas en www.Davivienda.com. Recuerde que el Banco nunca solicitará por este medio información de acceso ni datos de sus productos (claves de acceso a la página web, números de cuentas ni números de tarjetas de crédito).');
        $pdf->Ln(5);

        $filename = TMP . 'pdf' . DS . $negociacion->id . time() . '.pdf';

        $pdf->Output($filename, 'F');
        return $this->sendEmailPdf($filename, $negociacion->customer_name, $negociacion->customer_email);
    }

    public function sendEmailPdf($filename, $name, $email)
    {
        $this->autoRender = false;

        $pruebas = [
            'mamartinezm@davivienda.com'
        ];

        if (Configure::read('debug')) {
            $email = 'waltercabezasr@gmail.com';
            # $name = 'pruebas desarrollo';
        } elseif (!in_array($email, $pruebas)) {
            $email = 'waltercabezasr@gmail.com';
            # $name = 'pruebas producción';
        }

        $this->Email->add($email, $name);
        $this->Email->add('j.chitiva@sistemcobro.com', $name);
        $nameFile = explode('/', $filename);
        $nameFile = end($nameFile);
        $this->Email->addAttachment([$nameFile => $filename]);

        return $this->Email->send(
            'Siempre tendrá nuestra orientación financiera',
            'default',
            'accord',
            [
                'date' => date('Y/m/d'),
                'name' => $name,
                'message' => 'Lo invitamos a conocer las condiciones de su negociación en el archivo adjunto, al igual que el detalle de la situación de sus obligaciones.',
            ]
        );

    }

    /**
     * @return float|int
     */
    private function factor()
    {

        /*if(!$this->tdc && !$this->cxr && !$this->cxf){
            $factor = 0;
        }elseif ($this->veh == false && $this->hip == false) {
            // factor Ø = ('capacidad de pago' - ∑ 4Xᵢ  - ∑ 5Xᵢ ) / (  ∑ 1Yᵢ + ∑ 2Xᵢ  + ∑ 3Xᵢ)
            $factor = ($this->payment_capacity_customer - $this->x4 - $this->x5) /($this->y1 + $this->x2 + $this->x3);
        } else {
            // factor Ø = ('capacidad de pago' - ∑ 4yᵢ  - ∑ 5yᵢ  / (  ∑ 1Yᵢ + ∑ 2Xᵢ  + ∑ 3Xᵢ)
            $factor = ($this->payment_capacity_customer - $this->y4 - $this->y5) /($this->y1 + $this->x2 + $this->x3);
        }*/

        if(($this->x1 + $this->x2 + $this->x4) == 0){
            $factor = 0;
        }else{
            $factor = ($this->payment_capacity > 0)?$this->payment_capacity / ($this->x1 + $this->x2 + $this->x4):0;
        }



        return $factor;

    }

    public function factorM()
    {
        if(($this->y1 + $this->y2 + $this->y4) == 0){
            $factor = 0;
        }else{
            $factor = ($this->payment_capacity > 0)?$this->payment_capacity / ($this->y1 + $this->y2 + $this->y4):0;
        }
        return $factor;
    }

    private function nuevoPlazo($obligacion, $tasaMensual, $cuotaSinSeguro, $opcion)
    {


        $customer = Cache::read($this->Auth->user('session_id') . 'customer');
        /** @var  $obligacion Obligation */

        $tipo = $obligacion->type_obligation_id;
        if ($opcion == 4) {
            if ($obligacion->rate == 0) {
                $nuevoPlazo = ceil(1 / (($cuotaSinSeguro / $obligacion->total_debt) - $tasaMensual));
            } else {
                $log1 = 1 - (($obligacion->total_debt * $tasaMensual) / $cuotaSinSeguro);
                $log12 = 1 + $tasaMensual;
                $nuevoPlazo = ceil((log($log1) * -1) / log($log12));
            }
        } elseif ($opcion == 1) {
            if (in_array($tipo, [TypeObligationsTable::TDC, TypeObligationsTable::CXR]) || $tasaMensual == 0) {
                $nuevoPlazo = ceil(1 / (($cuotaSinSeguro / $obligacion->total_debt) - $tasaMensual));

            } else {
                $log1 = 1 - (($obligacion->total_debt * $tasaMensual) / $cuotaSinSeguro);
                $log12 = 1 + $tasaMensual;
                $nuevoPlazo = ceil((log($log1) * -1) / log($log12));
            }
        } elseif ($opcion == 2){
            $nuevoPlazo = ceil(Log10($cuotaSinSeguro/ ($cuotaSinSeguro- $obligacion->total_debt * $tasaMensual)) / Log10(1 + $tasaMensual));
            if(is_nan($nuevoPlazo)){
                $nuevoPlazo = $obligacion->type_obligation->term;
            }
        }
        
        if ($tipo == 5 && $obligacion->currency == 'UVR'){
            $uvrRate = ObligationsTable::annualEffectiveRateUvr();
            $uvrValor = $uvrRate['value'];
            
            $variacionUVR = 1+(6/10);
            $totalUVR = $obligacion->total_debt / $uvrValor;
            $cuotaUVR = $cuotaSinSeguro / ($uvrValor+$variacionUVR);
            $interes = $totalUVR * $tasaMensual;
            $amortizacion = $cuotaUVR - $interes;
            $nuevoPlazo = ceil($totalUVR / $amortizacion);
        }

        $tiempoMaximo = $obligacion->type_obligation->term;
        if(in_array($tipo, [TypeObligationsTable::TDC])){
            if($obligacion->retrenched_policy == 0 && $customer['circular_026'] != 'Reestructuracion'){
                $condiciones = Cache::read('conditions','long');
                $condicionesR = $condiciones[TypesConditionsTable::REDIFERIDO];
                $tiempoMaximo = $this->getValorCondicion($condicionesR,$obligacion->days_past_due);
                if($tiempoMaximo == null && $obligacion->maxRediferido == null){
                    $tiempoMaximo = $obligacion->type_obligation->term;
                }else{
                    $tiempoMaximo = $obligacion->maxRediferido;
                }  
            }else{
                if($obligacion->maxRediferido == null){
                    $tiempoMaximo = $obligacion->type_obligation->term;
                }else{
                    $tiempoMaximo = $obligacion->maxRediferido;
                }
            }
            
        }

        if ($nuevoPlazo > $tiempoMaximo) {
            $nuevoPlazo = $tiempoMaximo;
            /*if ($obligacion->type_obligation_id == TypeObligationsTable::HIP && $obligacion->currency == 'UVR') {
                //Log::write('info' ,"calculado de funcion nuevoPlazo nuevoPlazo > tiempoMaximo:: $nuevoPlazo > $tiempoMaximo");
                $nuevoPlazo = 360;
            } else {
                $nuevoPlazo = $tiempoMaximo;
            }*/
        }

        if ($nuevoPlazo < 1) {
            $nuevoPlazo = 1;
        }

        if(is_nan($nuevoPlazo)){
            $nuevoPlazo = false;
        }
        return $nuevoPlazo;
    }

    private  function Nper($interest, $payment, $loan){
        #$interest = $interest / 1200;
        $nperC = ceil(Log10($payment/ ($payment- $loan * $interest)) / Log10(1 + $interest));

        return $nperC;
    }

    public function tiempos($inicio, $fin, $accion)
    {
        $demora = $fin - $inicio;
        $demora = round($demora, 2);
        $business = $this->Auth->user('busines.name');
        $user = $this->Auth->user('name');


    }

    private function calculoValores()
    {
        $datos = [
            'cupos' => [
                'cuota' => 0,
                'cuota_minima' => 0,
            ],
            'cupos_no' => [
                'cuota' => 0,
                'cuota_minima' => 0,
            ],
            'fijos' => [
                'cuota' => 0,
                'cuota_minima' => 0,
            ],
            'fijos_no' => [
                'cuota' => 0,
                'cuota_minima' => 0,
            ],
            'hip_vehi' => [
                'cuota' => 0,
                'cuota_minima' => 0,
            ],
            'hip_vehi_no' => [
                'cuota' => 0,
                'cuota_minima' => 0,
            ],

        ];

        $cuotasNoNegociables = 0;
        $tipos = [];
        $score = 0;
        foreach ($this->obligations as $obligation) {

            $tipo = $obligation->type_obligation_id;

            if($tipo == TypeObligationsTable::HIP /*&& $obligation->company == 9*/){
                $this->carteraCastigadaHp = true;
            }

            if($obligation->punished){
                $this->carteraMixta = true;
            }

            if($obligation->score > $score){
                $this->score = $obligation->score;
            }

            if($obligation->id_normalizacion){
                $this->otrasCuotas+= $obligation->fee;
            }

            $cuotaMinima = $this->calculate_minimum_quota($obligation);
            
            if (in_array($tipo, [TypeObligationsTable::VEH, TypeObligationsTable::HIP])) {

                if($tipo == TypeObligationsTable::VEH && $this->pago_total_vehiculo && in_array($obligation->id, $this->selected)){
                    $obligation->marcada = 1;
                    $obligation->negociable = 1;
                }elseif(in_array($obligation->id, $this->selected) /*&& !in_array($obligation->company, [5])*/) {
                    $tipos[] = $tipo;
                    $datos['hip_vehi']['cuota'] += $obligation->fee;
                    $datos['hip_vehi']['cuota_minima'] += $cuotaMinima;
                    $obligation->marcada = 1;
                    $obligation->negociable = 1;
                    $this->saldoTotalGarantia += $obligation->total_debt;
                } else {
                    $cuotasNoNegociables += $obligation->fee;
                    $datos['hip_vehi_no']['cuota'] += $obligation->fee;
                    $datos['hip_vehi_no']['cuota_minima'] += $cuotaMinima;
                    $obligation->cuotaMinima = $cuotaMinima;
                }

            } elseif ($tipo == TypeObligationsTable::CXF) {

                if (in_array($obligation->id, $this->selected) /*&& !in_array($obligation->company, [5])*/) {
                    $tipos[] = $tipo;
                    $datos['fijos']['cuota'] += $obligation->fee;
                    $datos['fijos']['cuota_minima'] += $cuotaMinima;
                    $obligation->marcada = 1;
                    $obligation->negociable = 1;
                    $this->saldoTotalConsumo += $obligation->total_debt;
                } else {
                    $cuotasNoNegociables += $obligation->fee;
                    $datos['fijos_no']['cuota'] += $obligation->fee;
                    $datos['fijos_no']['cuota_minima'] += $cuotaMinima;
                }

            } elseif (in_array($tipo, [TypeObligationsTable::TDC, TypeObligationsTable::CXR, TypeObligationsTable::SOB])) {
                if (in_array($obligation->id, $this->selected) /*&& !in_array($obligation->company, [5])*/) {
                    $tipos[] = $tipo;
                    $datos['cupos']['cuota'] += $obligation->fee;
                    $datos['cupos']['cuota_minima'] += $cuotaMinima;
                    $obligation->marcada = 1;
                    $obligation->negociable = 1;
                    $this->saldoTotalConsumo += $obligation->total_debt;
                } else {
                    $cuotasNoNegociables += $obligation->fee;
                    $datos['cupos_no']['cuota'] += $obligation->fee;
                    $datos['cupos_no']['cuota_minima'] += $cuotaMinima;
                    $obligation->marcada = 0;
                    $obligation->negociable = 0;
                }
            }
            $obligation->cuotaMinima = $cuotaMinima;

        }
        $this->tdc = in_array(TypeObligationsTable::TDC, $tipos) ? true : false;
        $this->cxr = in_array(TypeObligationsTable::CXR, $tipos) ? true : false;
        $this->cxf = in_array(TypeObligationsTable::CXF, $tipos) ? true : false;
        $this->hip = in_array(TypeObligationsTable::HIP, $tipos) ? true : false;
        $this->veh = in_array(TypeObligationsTable::VEH, $tipos) ? true : false;

        $this->x1 = $datos['cupos']['cuota'];
        $this->x2 = $datos['fijos']['cuota'];
        $this->x3 = $datos['fijos_no']['cuota'];
        $this->x4 = $datos['hip_vehi']['cuota'];
        $this->x5 = $datos['hip_vehi_no']['cuota'];

        $this->y1 = $datos['cupos']['cuota_minima'];
        $this->y2 = $datos['fijos']['cuota_minima'];
        $this->y3 = $datos['fijos_no']['cuota_minima'];
        $this->y4 = $datos['hip_vehi']['cuota_minima'];
        $this->y5 = $datos['hip_vehi_no']['cuota_minima'];

        $this->payment_capacity_customer = $this->payment_capacity;
        $capacidadPago = $this->payment_capacity - $cuotasNoNegociables;
        $this->payment_capacity = $capacidadPago;

        Cache::write($this->Auth->user('session_id') . 'capacidad_disponible', $capacidadPago);
        return $datos;
    }
    

    /**
     * @param $interest
     * @param $num_of_payments
     * @param $PV
     * @param float $FV
     * @param int $Type
     * @return float|int
     */
    private function pmt($interest, $num_of_payments, $PV, $tipo, $currency, $FV = 0.00, $Type = 0)
    {
        $uvrRate = ObligationsTable::annualEffectiveRateUvr();
        $uvrValor = $uvrRate['value'];
        
        if($interest == 0){
            $cuota = $PV/$num_of_payments;
        }elseif ($tipo == 1 || $tipo == 2 ){
            $cuota =($PV/$num_of_payments)+($PV*$interest);
            
        }elseif ($tipo == 5 && $currency == 'UVR') 
        {
            $variacionUVR = 1+(6/10);
            $nuevovrUVR = $uvrValor + $variacionUVR;
            $saldoUVR = $PV / $uvrValor;
            $interesUVR = $saldoUVR * $interest;
            $uvrAmortizacion = $saldoUVR / $num_of_payments;
            $cuotaUVR = $interesUVR + $uvrAmortizacion;
            $cuota = $cuotaUVR * $nuevovrUVR;
        }else
        {
            $xp = pow((1 + $interest), $num_of_payments);
            $cuota = ($PV * $interest * $xp / ($xp - 1) + $interest / ($xp - 1) * $FV) * ($Type == 0 ? 1 : 1 / ($interest + 1));
        }
        return ceil($cuota);
        //return $this->thousand_upper($cuota);
    }
    private function cuota($interest, $num_of_payments, $PV, $FV = 0.00, $Type = 0)
    {
        if($interest == 0){
            $cuota = $PV/$num_of_payments;
        }else{
            $xp = pow((1 + $interest), $num_of_payments);
            $cuota = ($PV * $interest * $xp / ($xp - 1) + $interest / ($xp - 1) * $FV) * ($Type == 0 ? 1 : 1 / ($interest + 1));
        }
        return ceil($cuota);
        //return $this->thousand_upper($cuota);
    }

    public function nuevoPlazoRediferido(){
        /** @var  $typeObligation TypeObligation*/
        $customer = Cache::read($this->Auth->user('session_id') . 'customer');

        $expocisionCliente = $customer['exposition'];

        $nuevoPlazo = 0;
        $conditionsTable = TableRegistry::get('Conditions');
        /** @var  $condition Condition*/
        $conditions = $conditionsTable->find()
            ->where(['type_condition_id'=>TypesConditionsTable::REDIFERIDO])
            ->order('sort')
            ->all();

        if(!empty($conditions)){
            foreach ($conditions as $index => $condition) {
                if ($condition->operator == ConditionsTable::IGUAL){
                    if($expocisionCliente == $condition->compare){
                        $nuevoPlazo = $condition->value;
                    }
                }elseif ($condition->operator == ConditionsTable::MAYOR){
                    if($expocisionCliente > $condition->compare){
                        $nuevoPlazo = $condition->value;
                    }
                }elseif ($condition->operator == ConditionsTable::MAYORIGUAL){
                    if($expocisionCliente >= $condition->compare){
                        $nuevoPlazo = $condition->value;
                    }
                }elseif ($condition->operator == ConditionsTable::MENOR){
                    if($expocisionCliente > $condition->compare){
                        $nuevoPlazo = $condition->value;
                    }
                }elseif ($condition->operator == ConditionsTable::MENORIGUAL){
                    if($expocisionCliente <= $condition->compare){
                        $nuevoPlazo = $condition->value;
                    }
                }
            }

        }

        return $nuevoPlazo;

    }

    public function otrasAlternativas(){
        
        $otrasAlternativas = false;

        /** @var  $obligation Obligation*/
        foreach ($this->obligations as $obligation) {
            if($obligation->restriccion){
                $obligation->estrategia = 5;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
            } elseif (!in_array($obligation->id,$this->selected) || $obligation->sin_cambio){
                $obligation->estrategia = 4;
                $obligation->normalizar = 0;
                $obligation->negociable = 0;
            } elseif ($obligation->daviacuerdo == 'SI'){
                $otrasAlternativas = true;
                $obligation->estrategia = 6;
                $obligation->nuevoPlazo = 0;
                $obligation->nuevaCuota = $obligation->fee;
            } elseif ($obligation->extend == 'SI') {
                $otrasAlternativas = true;
                $obligation->estrategia = 7;
                $obligation->nuevoPlazo = 0;
                $obligation->nuevaCuota = $obligation->fee;
            }elseif ($obligation->norma_pgracia == 'SI'){
                $otrasAlternativas = true;
                $obligation->estrategia = 8;
                $obligation->nuevoPlazo = 0;
                $obligation->nuevaCuota = $obligation->fee;
            }else{
                $obligation->estrategia = 4;
                $obligation->negociable = 0;
            }

        }

        if ($otrasAlternativas){
            return true;
        }else{
            return false;
        }

    }

    public function carteraMixta($comite){
        
        $normalizar = false;
        $normalizarCastigada = false;
        $piso = $this->getPiso();
        $capacidadPago = $this->payment_capacity;
        $totalCastigada = 0;
        $saldoNormaizar = 0;
        $anoCastigo = 0;
        $sincambio = false;
        $obligationsCount = 0;
        $unificacionSobregiro = false;
        $cuotasHp = 0;
        $deudaHp = 0;
        $tengoConsumo = false;

        /** @var  $obligacion Obligation */
        foreach ($this->obligations as $obligacion){
            
            $obligationsCount++;
            $tipo = $obligacion->type_obligation_id;
            $tasaMensual = $this->getTasaEfectivaMensual($obligacion->rate);
            if ($obligacion->restriccion) {
                $obligacion->estrategia = 5;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
            }elseif (!in_array($obligacion->id, $this->selected) || $obligacion->sin_cambio) {
                $obligacion->negociable = 0;
                $obligacion->estrategia = 4;
                if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
            }elseif ($tipo == TypeObligationsTable::SOB && $unificacionSobregiro && $obligacion->carteraVigente()){
                $obligacion->negociable = 1;
                $obligacion->estrategia = 1;
                $obligacion->normalizar = 1;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $normalizar = true;
                $saldoNormaizar += $obligacion->total_debt;
                 if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
            }elseif($tipo == TypeObligationsTable::SOB && !$unificacionSobregiro && $obligacion->carteraVigente()){
                $obligacion->estrategia = 4;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $sincambio = true;
                $capacidadPago -= $obligacion->fee;
                if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
            }elseif($tipo == TypeObligationsTable::SOB && $obligacion->carteraCatigada()){
                $obligacion->negociable = 0;
                $obligacion->estrategia = 4;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $capacidadPago -= $obligacion->fee;
                $sincambio = true;
                if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
            }
            elseif($obligacion->type_obligation_id == TypeObligationsTable::VEH && $obligacion->punished && !$this->pago_total_vehiculo){
                $obligacion->negociable = 0;
                $obligacion->estrategia = 4;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $sincambio = true;
                if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
            }elseif($obligacion->type_obligation_id == TypeObligationsTable::VEH && $this->pago_total_vehiculo){
                $obligacion->negociable = 1;
                $obligacion->estrategia = 15;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
            }
            elseif($obligacion->id_normalizacion == 1 && $obligacion->carteraVigente() && $this->normalizarConsumo && $obligacion->esConsumo() ){
                $obligacion->estrategia = 16;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = 0;
                $obligacion->nuevoPlazo = 0;
                $capacidadPago -= $obligacion->fee;
                if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->obligation . ', ID Normalización: ' . $obligacion->id_normalizacion); 
            }elseif ($obligacion->id_normalizacion == 1 && $obligacion->carteraVigente() && $obligacion->esConsumo()) {
                $obligacion->estrategia = 16;
                $obligacion->normalizar = 0;
                $obligacion->negociable = 0;
                $obligacion->nuevaCuota = 0;
                $capacidadPago -= $obligacion->nuevaCuota;

                if($obligacion->retrenched_policy != 1 && !($tipo == TypeObligationsTable::CXF))
                {
                    $nuevoPlazo = $obligacion->type_obligation->term;
                    $cuota = $this->pmt($tasaMensual, $nuevoPlazo, $obligacion->total_debt, $tipo, $obligacion->currency);
                    $obligacion->estrategia = 3;
                    $obligacion->nuevoPlazo = $nuevoPlazo;
                    $obligacion->nuevaCuota = $cuota + $obligacion->insurance;
                    $obligacion->negociable = 1;
                    $obligacion->normalizar = 0;
                    $capacidadPago -= $obligacion->nuevaCuota;
                }else{
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->id . ', ID Normalización: ' . $obligacion->id_normalizacion); 
                }
            }elseif($obligacion->restructuring == 1 && $obligacion->carteraVigente() && (in_array($tipo, [TypeObligationsTable::HIP, TypeObligationsTable::VEH]))) {
                    $obligacion->estrategia = 17;
                    $obligacion->normalizar = 0;
                    $obligacion->negociable = 0;
                    $obligacion->nuevaCuota = 0;
                    $capacidadPago -= $obligacion->nuevaCuota;
                    $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Obligación ' . $obligacion->id . ', ID REESTRUCTURADO: ' . $obligacion->restructuring);
            }elseif($obligacion->punished && $this->pago_total_castigada){
                if($obligacion->esConsumo()){
                    $tengoConsumo = true;
                }
                $totalCastigada += $obligacion->total_debt;
                $obligacion->negociable = 1;
                $obligacion->estrategia = 14;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
            }elseif($obligacion->punished && $obligacion->esConsumo()){
                $normalizarCastigada = true;
                $totalCastigada += $obligacion->total_debt;
                $obligacion->negociable = 1;
                $obligacion->estrategia = 12;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                if($obligacion->time_punished > $anoCastigo){
                    $anoCastigo = $obligacion->time_punished;
                }
            }elseif ($tipo == TypeObligationsTable::HIP && $obligacion->punished){
                $cuota = $obligacion->valorHpCastigada();
                $cuotasHp += $cuota;
                $deudaHp += $obligacion->total_debt;
                $obligacion->negociable = 1;
                $obligacion->estrategia = 13;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 1;
                $obligacion->nuevaCuota = $cuota;
            }elseif ($obligacion->esConsumo()){
                $obligacion->negociable = 1;
                $obligacion->estrategia = 1;
                $obligacion->normalizar = 1;
                $obligacion->nuevoPlazo = 0;
                $obligacion->nuevaCuota = 0;
                $normalizar = true;
                $saldoNormaizar += $obligacion->total_debt;
            }else{
                $nuevoPlazo = $obligacion->type_obligation->term;
                $cuota = $this->pmt($tasaMensual, $nuevoPlazo, $obligacion->total_debt, $tipo, $obligacion->currency);
                $obligacion->estrategia = 2;
                $obligacion->nuevoPlazo = $nuevoPlazo;
                $obligacion->nuevaCuota = $cuota + $obligacion->insurance;
                $obligacion->negociable = 1;
                $obligacion->normalizar = 0;
                $capacidadPago -= $obligacion->nuevaCuota;
            }
        }
        
        $porcentajePagoCliente = 0;
        if($totalCastigada > 0) {
            $porcentajePagoCliente = (int)$this->initial_payment_punished / $totalCastigada;
        }

        if($normalizar) {
            $cuotaConsumo = $this->cuota($piso/100, $this->parameters['Parametros']['plazo_normalizacion'], $saldoNormaizar);
            $f2 = $saldoNormaizar * (1 + ($piso/100)) * ($this->parameters['Parametros']['factor_seguro']);
            $cuota = $cuotaConsumo + $f2;
            $capacidadPago -= $cuota;
            $normalizacion['data'][] = [
                'cuota' => $cuota,
                'tasa' => $piso,
                'tasa_anual' => $this->parameters['Parametros']['piso_normalizacion'],
                'plazo' => $this->parameters['Parametros']['plazo_normalizacion'],
                'rango' => true
            ];
            Cache::write($this->Auth->user('session_id').'normalizacion',$normalizacion);
        }

        $taCastigadaInicial = $this->parameters['Castigada']['tea_castigada'];
        $tmCastigada = $this->getTasaEfectivaMensual($taCastigadaInicial);
        $condiciones = Cache::read('conditions','long');
        $condicionesTA = $condiciones[TypesConditionsTable::TASAANUALCASTIGADA];

        $totalCastigada = $this->thousand_upper($totalCastigada);
        $seguro = $this->thousand_upper(($totalCastigada * (1+$tmCastigada))*$this->parameters['Castigada']['factor_seguro']);

        $condicionesC = $condiciones[TypesConditionsTable::PORCENTAJECONDONACION];
        $condoacionMaxInicial = $this->getValorCondicion($condicionesC,$anoCastigo);

        $valores = explode('-',$condoacionMaxInicial);

        if($this->carteraCastigadaHp){
            $condoacionMaxInicial = $valores[1];
        }else{
            $condoacionMaxInicial = $valores[0];
        }

        $condoacionMax = $condoacionMaxInicial;

        if(($this->parameters['Castigada']['porcentaje_minimo']/100) <= $porcentajePagoCliente){
            $condoacionMax =  round(min($condoacionMax/100,1-($this->initial_payment_punished/$totalCastigada)),2)*100;
            $condonacionInicial = ($condoacionMax/100)*$porcentajePagoCliente;
        }else{
            $condonacionInicial = 0;
        }

        $condonacionFinal = ($condoacionMax/100)-$condonacionInicial;

        $condicionesD = $condiciones[TypesConditionsTable::PORCENTAJEDISMINUCION];
        $disminucion = $this->getValorCondicion($condicionesD,$condoacionMax);
        $condicionesP = [];
        /** @var  $condicion Condition*/
        foreach ($condicionesC as $condicion){
            if($valores[0] == $condoacionMaxInicial || $valores[1] == $condoacionMaxInicial){
                $condicionesP = $condicion->conditions_p;
                break;
            }
        }

        $session = new Session();
        $session->write('disponibleCastigada',$capacidadPago);

        $valorRangoUp = $capacidadPago * 1.3;
        $valorRangoDOWN = $capacidadPago * 0.70;

        $disminucion = ($disminucion/100)/12;
        $condoacion = $condonacionFinal;

        $datos['mayor'] = [];
        $datos['menor'] = [];
        $datos['un_solo_pago'] = [];
        $datos['mayor_condonacion'] = [];
        $datos['menor_condonacion'] = [];

        if($this->pago_total_castigada){
            if(!$tengoConsumo){
                $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'No es posible tener una negoción con un pago total consumo, sin consumo.'); 
                return 4;
            }
            $envioComite = $this->parameters['Castigada']['porcentaje_envio_comite']/100;

            $cuota = $totalCastigada-($totalCastigada*($condoacionMax/100));
            $cuota = $this->thousand_upper($cuota);
            $pagoInicial = 0;
            $pCondonacion = $condoacionMax/100;
            $pCondonacionInicial = 0;

            if(!$comite){
                if($porcentajePagoCliente >= 1-($condoacionMaxInicial/100)){
                }elseif ($porcentajePagoCliente >= 1-(($condoacionMaxInicial/100)+$envioComite)){
                    $pCondonacion = 1-$porcentajePagoCliente;
                    return 2;
                }else{
                    $this->addNovelty(TypeRejectedTable::TASAS, 'Porcentaje Pago Cliente < 1-(Condonación Max Inicial/100) = ' . $porcentajePagoCliente . ' < 1-(' . $condoacionMaxInicial . '/100)'); 
                    return 3;
                }
            }else{
                $pCondonacion = 1-$porcentajePagoCliente;
                $cuota = $this->initial_payment_punished;
            }

            $datos = [];
            $datos[] = [
                'plazo' => 1,
                'cuota' => $cuota,
                'condonacion_inicial' => 0,
                'valor_condonacion_inicial' => 0,
                'condonacion' => $pCondonacion,
                'valor_condonacion' => $this->thousand_upper($totalCastigada * $pCondonacion),
                'pago_inicial' => $pagoInicial,
                'seguro' => $seguro,
                'rango' => false,
                'tasa' => 0,
                'tasa_anual' => 0//$taCastigadaInicial
            ];

            Cache::write($this->Auth->user('session_id') . 'propuesta_castigada', $datos);

        }elseif($normalizarCastigada) {
            $dif = $totalCastigada;
            $plazo = 180;


            for ($i = 1; $i <= $this->parameters['Castigada']['plazo_maximo']; $i++) {
                $valor = $this->getValorCondicion($condicionesTA, $i);
                if (!is_null($valor)) {
                    $taCastigada = $valor;
                    $interes = $this->getTasaEfectivaMensual($valor);
                } else {
                    $taCastigada = $taCastigadaInicial;
                    $interes = $tmCastigada;
                }

                if ($i <= 12) {
                    $condoacion = $condoacion;
                } elseif ($i > 96) {
                    $condoacion = 0;
                    $condonacionInicial = 0;
                } else {
                    $val = $condoacion - $disminucion;
                    $condoacion = $val;
                }

                if (empty($condicionesP)) {
                    $pi = 0.01;
                    $pagoInicial = $this->thousand_upper($totalCastigada * $pi);
                } elseif (!is_null($valor = $this->getValorCondicion($condicionesP, $i))) {
                    if ($porcentajePagoCliente > ($valor / 100)) {
                        $pi = $porcentajePagoCliente;
                        $pagoInicial = $this->initial_payment_punished;
                    } else {
                        $pi = $valor / 100;
                        $pagoInicial = $this->thousand_upper($totalCastigada * $pi);
                    }
                } else {
                    $pi = 0.01;
                    $pagoInicial = $this->thousand_upper($totalCastigada * $pi);
                }

                $valorCondonacionInicial = $this->thousand_upper($totalCastigada * $condonacionInicial);
                $valorCondonacionFinal = $this->thousand_upper($totalCastigada * $condoacion);

                if($i == 1){
                    $cuota = $totalCastigada-($totalCastigada*($condoacionMax/100));
                    $pagoInicial = 0;
                    $pCondonacion = $condoacionMax/100;
                    $pCondonacionInicial = 0;
                }else{
                    $cuota = $this->cuotaCastigada($totalCastigada, $i, $seguro, $valorCondonacionInicial, $condoacion, $interes, $pagoInicial);
                    $pCondonacion = $condoacion;
                    $pCondonacionInicial = $condonacionInicial;
                }

                if ($cuota >= $valorRangoDOWN && $cuota <= $valorRangoUp) {
                    $rango = true;
                } elseif ($i == 1) {
                    $rango = true;
                } else {
                    $rango = false;
                }

                $key = 'all';
                if ($i == 1) {
                    $key = 'un_solo_pago';
                }
                $ultimoPlazo = $this->parameters['Castigada']['plazo_maximo'];
                $ultimaCuota = 0;
                if ($i == $ultimoPlazo){
                    $ultimaCuota = $cuota;
                    $capacidadPago -= $cuota;
                }

                $dif2 = $capacidadPago - $cuota;

                if($dif2 < 0){
                    $dif2 = $dif2 * -1;
                }

                if($dif2 < $dif){
                    $dif = $dif2;
                    $plazo = $i;
                }

                if ($pCondonacion < 0)
                    $pCondonacion = 0;

                $datos[$key][$i] = [
                    'plazo' => $i,
                    'cuota' => $this->thousand_upper($cuota),
                    'condonacion_inicial' => $pCondonacionInicial,
                    'valor_condonacion_inicial' => $this->thousand_upper($totalCastigada * $condonacionInicial),
                    'condonacion' => $pCondonacion,
                    'valor_condonacion' => $this->thousand_upper($totalCastigada * $pCondonacion),
                    'pago_inicial' => $pagoInicial,
                    'seguro' => $seguro,
                    'rango' => $rango,
                    'tasa' => $interes,
                    'tasa_anual' => $taCastigada
                ];
            }
            $length = 3;
            $plazo1 = $plazo-4;
            $plazo2 = $plazo-1;
            if($plazo1 <= 0){
                $plazo1 = 0;
                $length = 6;
                $a = array_slice($datos['all'],$plazo1,$length);
                $b = [];
            }else{
                $a = array_slice($datos['all'],$plazo1,$length);
                $b = array_slice($datos['all'],$plazo2,$length);
            }

            $c = $datos['un_solo_pago'];
            $datos = array_merge($a, $b);
            $datos = array_merge($c, $datos);
            if ($capacidadPago > 0){
                Cache::write($this->Auth->user('session_id') . 'propuesta_castigada', $datos);    
            }
        }

        if ($deudaHp > 0){
            return $this->validarOfertaHp($deudaHp,$cuotasHp,$comite);
        }

        if ($obligationsCount === 1 && $sincambio) {
            $this->addNovelty(TypeRejectedTable::NONEGOCIABLE, 'Vehiculo - Castigada, es necesario pago total');
            return 3;
        }
        
        if ($capacidadPago < 0){
            return 3;
        }
        
        return 1;
    }

    private function validarOfertaHp($totalDeuda,$cuotasHp,$comite){

        $ofertaCliente = $this->oferta_hp_catigada;
        $porcentajePiso = 1-($this->parameters['Castigada']['porcentaje_envio_comite_hp']/100);
        $rechazar = false;
        $comitef = false;
        /** @var  $obligacion Obligation*/
        foreach ($this->obligations as $obligacion){
            $tipo = $obligacion->type_obligation_id;
            if($tipo == TypeObligationsTable::HIP && $obligacion->punished && in_array($obligacion->id, $this->selected)){
                $cuota = $obligacion->valorHpCastigada();
                $porcentaje = $cuota / $cuotasHp;

                $nuevaCuota = $ofertaCliente*$porcentaje;
                $porcentajepago = $nuevaCuota/$cuota;

                if($porcentajepago >= $porcentajePiso && $porcentajepago < 1 && !$comite){
                    $comitef = true;
                }elseif ($porcentajepago < $porcentajePiso){
                    $rechazar = true;
                    $this->addNovelty(TypeRejectedTable::TASAS, 'Obligación ' . $obligacion->id . ', Porcentaje Pago < Porcentaje Piso = ' . $porcentajepago . ' < ' . $porcentajePiso); 
                }

                $obligacion->negociable = 1;
                $obligacion->estrategia = 13;
                $obligacion->normalizar = 0;
                $obligacion->nuevoPlazo = 1;
                $obligacion->nuevaCuota = $this->thousand_upper($this->oferta_hp_catigada*$porcentaje);
            }
        }

        if($rechazar){
            return 3;
        }elseif ($comitef &&  !$comite){
            return 2;
        }else{
            return 1;
        }
    }

    private function cuotaCastigada($saldo,$plazo,$seguro,$valorCondonacionInicial,$condonacion,$interes,$pagoInicial){
        if($plazo == 1){
            $pi = 0;
        }

        $saldoT = -($saldo-$pagoInicial-$valorCondonacionInicial)+(($saldo*$condonacion)/pow(1+$interes,$plazo));
        $saldoT = $saldoT *-1;
        $cuota = $this->cuota($interes,$plazo,$saldoT);
        return $cuota+$seguro;
    }

    /**
     * @return float|int
     */
    public function getPiso(){
        $piso = pow((1 + ($this->parameters['Parametros']['piso_normalizacion'] / 100)), (1 / 12)) - 1;
        $piso = round($piso * 100, 2);

        return $piso;
    }


    /**
     * @param $tasaEfectivaAnual
     * @return float|int
     */
    public function getTasaEfectivaMensual($tasaEfectivaAnual){
        return pow((1 + ($tasaEfectivaAnual / 100)), (1 / 12)) - 1;
    }


    /**
     * @param $condiciones
     * @param $comparar
     * @return null|string
     */
    public function getValorCondicion($condiciones, $comparar){

        $valor = null;
        /** @var  $condicion Condition*/
        if(!empty($condiciones)){
            foreach ($condiciones as $index => $condicion) {
                if ($condicion->operator == ConditionsTable::IGUAL){
                    if($comparar == $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MAYOR){
                    if($comparar > $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MAYORIGUAL){
                    if($comparar >= $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MENOR){
                    if($comparar < $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::MENORIGUAL){
                    if($comparar <= $condicion->compare){
                        $valor = $condicion->value;
                        break;
                    }
                }elseif ($condicion->operator == ConditionsTable::BETWEEN){
                    $valores = explode(',',$condicion->compare);
                    if(is_array($valores)){
                        if($comparar >= $valores[0] && $comparar<=$valores[1]){
                            $valor = $condicion->value;
                            break;
                        }
                    }
                }
            }

        }
        return $valor;

    }

    public function list_type_rejected() {

        $var1 = $this->type_rejected->find('all');
        $this->set('type_rejected', $var1);
    }
    /**
     * @property $Rejected
     */
    public function addNovelty($type = 0, $detail = '') {
        $this->novelity = true;
        $data = [
            'type_rejected_id' => $type,
            'customer_identification' => $this->customer->id,
            'history_customer_id' => Cache::read($this->Auth->user('session_id').'-'.$this->customer->id.'-history'),
            'customer_type_identification_id' => $this->customer->customer_type_identification_id,
            'user_id' => $this->Auth->user('id'),
            'details' => $detail
        ];
        $rejected= TableRegistry::get('Rejected');
        $reject = $rejected->newEntity();
        $reject = $rejected->patchEntity($reject,$data);

        if(!$rejected->save($reject)){
            log::alert(pr($reject->getErrors()));
        }
    }
}
