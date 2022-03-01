<?php
namespace App\Model\Entity;

use App\Model\Table\ConditionsTable;
use App\Model\Table\ObligationsTable;
use App\Model\Table\TypeObligationsTable;
use App\Model\Table\TypesConditionsTable;
use Cake\Cache\Cache;
use Cake\I18n\Date;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Obligation Entity
 *
 * @property int $id
 * @property string $obligation
 * @property string $maskobligation
 * @property string $total_debt
 * @property string $saldo_contable
 * @property string $vr_liquidacion
 * @property string $daviacuerdo
 * @property string $redif_conyunt
 * @property string $pt_consumo
 * @property string $pt_castigada
 * @property string $extend
 * @property string $norma_pgracia
 * @property string $approved_quota
 * @property string $fee
 * @property string $insurance
 * @property string $minimum_payment
 * @property string $currency
 * @property string $rate
 * @property string $tasaMensual
 * @property string $step
 * @property string $mcc_product
 * @property int $days_past_due
 * @property bool $restructuring
 * @property bool $punished
 * @property bool $time_punished
 * @property int $customer_id
 * @property int $customer_type_identification_id
 * @property int $type_obligation_id
 * @property int $nuevoPlazo
 * @property int $company
 * @property int $restriccionJuridica
 * @property bool $restriccion
 * @property bool $sin_cambio
 * @property int $acierta
 * @property int $plazo_inicial
 * @property int $modelo_automotor
 * @property double $saldo_mora_act
 * @property double $acomulated_payment
 * @property double $interests_arrears_act
 * @property int $pagoSugerido
 * @property int $pagoReal
 * @property int $cuotaMinima
 * @property int $id_normalizacion
 * @property int $pagare
 * @property string $ciudad_radicacion
 * @property int $maxRediferido
 * @property int $maxUnificacion
 * @property \Cake\I18n\FrozenTime $minimum_payment_date
 * @property \Cake\I18n\FrozenTime $punishment_date
 * @property \Cake\I18n\FrozenTime $fecha_etapa_actual
 * @property \Cake\I18n\FrozenTime $fecha_avaluo
 * @property \Cake\I18n\FrozenTime $fecha_apertura
 *
 * @property \App\Model\Entity\Customer $customer
 * @property \App\Model\Entity\TypeObligation $type_obligation
 * @property \App\Model\Entity\TypeObligation $charge
 * @property \App\Model\Entity\AdjustedObligationsDetail[] $adjusted_obligations_details
 */
class Obligation extends Entity
{

    var $cuotaMinima = 0;
    var $tasaMensual = 0;
    var $marcada = 0;
    var $nuevoPlazo = 0;
    var $nuevaTasa = 0;
    var $negociable = 0;
    var $estrategia = 0;
    var $condonacion = 0;
    var $normalizar = 0;
    var $nuevaCuota = 0;
    var $estrategias = [
        0 => '',
        1 => 'Unificación de Obligaciones', //Normalizar
        2 => 'Ampliación de plazo', //Reestructurar
        3 => 'Rediferir', // Rediferir
        4 => 'Sin cambio',
        5 => 'Restricción Juridica',
        6 => 'REPMAS',
        7 => 'Prorroga',
        8 => 'Periodo de gracia',
        9 => 'RECLASIFICACION TITU',
        10 => 'SEGURO DESEMPLEO',
        11 => 'Pago Total Consumo',
        12 => 'ACPK',
        13 => 'Pago Total',
        14 => 'Pago Total',
        15 => 'Pago Total Vehiculo',
        16 => 'No renormalización',
        17 => 'No restructurable'
    ];

    var $restriccion = false;
    var $sin_cambio = false;
    var $restriccionJuridica = 0;
    var $maskobligation = '';
    var $punished = false;
    var $pagoSugerido = 0;
    var $pagoReal = 0;


    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];

    public function esConsumo(){
        /** @var  $obligacion Obligation*/
        if(in_array($this->type_obligation_id, [TypeObligationsTable::TDC, TypeObligationsTable::CXR, TypeObligationsTable::CXF])){
            return true;
        }else{
            return false;
        }
    }

    public function carteraVigente(){
        if ($this->company == 1 || $this->company == 5){
            return true;
        }else{
            return false;
        }
    }

    public function carteraCatigada(){
        if ($this->company == 9){
            return true;
        }else{
            return false;
        }
    }

    public function esHIPVEH(){
        /** @var  $obligacion Obligation*/
        if(in_array($this->type_obligation_id, [TypeObligationsTable::VEH, TypeObligationsTable::HIP])){
            return true;
        }else{
            return false;
        }
    }
    

    private function acierta(){
        $condiciones = Cache::read('conditions','long');
        $condicionesC = $condiciones[TypesConditionsTable::POLITICAACIERTA];
        $aciertaParametro = ConditionsTable::getValorCondicion($condicionesC,$this->days_past_due);

        if($this->acierta < $aciertaParametro){
            return true;
        }else{
            return false;
        }
    }

    private function conProceso(){

        $legalCodes = TableRegistry::get('LegalCodes');
        /** @var  $code LegalCode*/
        $code = $legalCodes->find()->where(['code'=>$this->step])->first();

        if($code){
            if($code->in_process){
                return true;
            }else{
                return false;
            }
        }else{
            return false;
        }
    }

    public function politicaAcierta(){

        if($this->company == 9){
            return true;
        }elseif (!$this->conProceso() && $this->acierta()){
            return true;
        } elseif (!$this->conProceso()){
            return true;
        }else{
            return false;
        }
    }

    public function valoresSegunEtapa($evaluador,$valorAvaluo,$fechaAvaluo,$dParuqeadero = 0, $dInpuesto = 0, $dComparendos = 0, $dGastos = 0){

        /** @var  $etapaActual LegalCode*/
        /** @var  $etapa LegalCode*/
        /** @var  $valor ValueStage*/
        /** @var  $ciudadPrincipal CityOffice*/
        /** @var  $otraCiudad CityOffice*/

        if(empty($this->step)){
            $this->step = 'SPRO';
        }

        $settings = Cache::read('settings', 'long');
        $inflacion = $settings['Calculadora vehiculo']['inflacion'];
        $inflacionMensual =  pow((1 + ($inflacion / 100)), (1 / 12)) - 1;

        $descuento = $settings['Calculadora vehiculo']['tasa_descuento'];
        $descuentoMensual =  pow((1 + ($descuento / 100)), (1 / 12)) - 1;

        $comparendos = $settings['Calculadora vehiculo']['comparendos'];
        $gastosM = $settings['Calculadora vehiculo']['gastos_mantenimiento'];
        $impuestos = $settings['Calculadora vehiculo']['impuestos'];

        $inflacion = $settings['Calculadora vehiculo']['inflacion'];

        $valorLtv = $settings['Calculadora vehiculo']['maximo_ltv'];

        $porcentajePerito = $settings['Calculadora vehiculo']['valor_venta_avaluo']/100;
        $porcentajeFasecolda = $settings['Calculadora vehiculo']['avaluo_fasecolda']/100;
        $porcentajeMotor = $settings['Calculadora vehiculo']['maximo_ltv']/100;
        $condonacionCapitalExperto = $settings['Calculadora vehiculo']['condonacion_capital_experto']/100;

        $cityOffices = TableRegistry::get('CityOffices');


        $ciudadPrincipal = $cityOffices->find()->where(['code' => $this->ciudad_radicacion])->first();
        $otraCiudad = $cityOffices->find()->where(['id' => 24])->first();
        $costoParqueaderos = [
            'sin_captura' => $otraCiudad->parking_no_capture,
            'convenio' => $otraCiudad->parking_agreement,
            'sin_convenio' => $otraCiudad->parking_no_agreement,
        ];
        $idCiudad = 0;
        if($ciudadPrincipal){
            $idCiudad = $ciudadPrincipal->id;
            $costoParqueaderos = [
                'sin_captura' => $ciudadPrincipal->parking_no_capture,
                'convenio' => $ciudadPrincipal->parking_agreement,
                'sin_convenio' => $ciudadPrincipal->parking_no_agreement,
            ];
        }

        $idCiudades = [
            $otraCiudad->id,
            $idCiudad
        ];

        $valueStages = TableRegistry::get('ValueStages');
        $valores = $valueStages->find()->where(['city_offices_id in' => $idCiudades])->all();
        $costos = [];

        foreach ($valores as $valor){
            $costos[$valor->legal_codes_id.'-'.$valor->city_offices_id] = $valor->value;
        }

        $legalCodes = TableRegistry::get('LegalCodes');
        $etapas = $legalCodes->find()->where(['legal_stage' => 1])->orderAsc('order_legal')->all();
        $etapaActual = $legalCodes->find()->where(['code'=>$this->step])->first();

        if(empty($this->parqueadero_convenio)){
            $ubicacion = 'sin_captura';
        }elseif ($this->parqueadero_convenio == 'Si'){
            $ubicacion = 'convenio';
        }elseif ($this->parqueadero_convenio == 'No'){
            $ubicacion = 'sin_convenio';
        }else{
            $ubicacion = 'sin_captura';
        }

        $informacion = [];
        $informacion['ciudad'] = $this->ciudad_radicacion;
        $tiempoTotal = 0;
        $tiempoTotalEtapas = 0;
        $tiempoTrascurrido = 0;

        foreach ($etapas as $etapa){
            $tiempoTotal += $etapa->term;
            $tiempoTotalEtapas += ($etapa->code == 'SPRO')?0:$etapa->term;
        }

        foreach ($etapas as $etapa){


            $tiempoTotal -= $etapa->term;
            if($tiempoTotal < 0.02){
                $tiempoTotal = 0;
            }
            $tiempoTrascurrido += $etapa->term;
            $costo = (isset($costos[$etapa->id.'-'.$idCiudad]))?$costos[$etapa->id.'-'.$idCiudad]:$costos[$etapa->id.'-'.$otraCiudad->id];
            $costoMensual = ($costo == 0)?0:$costo/$etapa->term;
            $probabilidadLlegarEtapa = min(($etapa->probability/$etapaActual->probability),1);
            $costoMensualP = $costoMensual*pow(1+$inflacionMensual,$tiempoTrascurrido)*$probabilidadLlegarEtapa;
            $costoParqueadero = (!$etapa->parking)?0:$costoParqueaderos[$ubicacion];

            $costoEtapaPro = $etapa->term*(365/12)*$costoParqueadero*$probabilidadLlegarEtapa;
            $costoEtapaP = $costoEtapaPro * pow(1+$inflacionMensual,$tiempoTrascurrido);

            $costoComparendoP = 0;
            $costoImpuestosP = 0;
            $costoGastosP = 0;
            if($etapa->code == 'ADJU') {
                $costoComparendoP = $comparendos * pow(1 + $inflacionMensual, $tiempoTotalEtapas) * $probabilidadLlegarEtapa;
                $costoImpuestosP = $impuestos * pow(1 + $inflacionMensual, $tiempoTotalEtapas) * $probabilidadLlegarEtapa;
                $costoGastosP = $gastosM * pow(1 + $inflacionMensual, $tiempoTotalEtapas) * $probabilidadLlegarEtapa;
            }

            $informacion['etapas'][] = [
                'estapa' => $etapa->description,
                'codigo' => $etapa->code,
                'probabilidad_etapa' => $etapa->probability,
                'probabilidad_llegar_etapa' => $probabilidadLlegarEtapa,
                'tiempo' => $etapa->term,
                'tiempo_fin' => $tiempoTotal,
                'tiempo_trascurrido' => $tiempoTrascurrido,
                'tiempo_total_etapas' => $tiempoTotalEtapas,
                'costo' => $costo,
                'costo_mensual' => $costoMensual,
                'costo_mensual_p' => $costoMensualP,
                'costo_parqueadero' => $costoParqueadero,
                'costo_parqueadero_etapa_pro' => $costoEtapaPro,
                'costo_parqueadero_etapa_p' => $costoEtapaP,
                'costo_comparendo_p' => $costoComparendoP,
                'costo_impuestos_p' => $costoImpuestosP,
                'costo_gastos_p' => $costoGastosP,
            ];
        }

        $informacion2 = array_reverse($informacion['etapas'],true);

        $valorAnteriorCj = 0;
        $valorAnteriorCp = 0;
        $valorAnteriorCc = 0;
        $valorAnteriorCi = 0;
        $valorAnteriorCg = 0;

        $informacion['etapas'] = [];

        foreach ($informacion2 as $key => $etapa){
            $informacion['etapas'][$etapa['codigo']] = $etapa;

            $informacion['etapas'][$etapa['codigo']]['vp_costo_juridico'] =  (($etapa['tiempo'] *  $etapa['costo_mensual_p'])+$valorAnteriorCj)/pow(1+$descuentoMensual,$etapa['tiempo']);

            $valorAnteriorCj = $informacion['etapas'][$etapa['codigo']]['vp_costo_juridico'];

            $informacion['etapas'][$etapa['codigo']]['vp_costo_parqueadero'] =  (($etapa['costo_parqueadero_etapa_p']+$valorAnteriorCp))/pow(1+$descuentoMensual,$etapa['tiempo']);

            $valorAnteriorCp = $informacion['etapas'][$etapa['codigo']]['vp_costo_parqueadero'];

            if($valorAnteriorCc == 0){
                $informacion['etapas'][$etapa['codigo']]['vp_costo_comparendos'] =  (($etapa['costo_comparendo_p']));
            }else{
                $informacion['etapas'][$etapa['codigo']]['vp_costo_comparendos'] =  $valorAnteriorCc/pow(1+$descuentoMensual,$etapa['tiempo_fin']);
            }
            $valorAnteriorCc = $informacion['etapas'][$etapa['codigo']]['vp_costo_comparendos'];

            if($valorAnteriorCi == 0){
                $informacion['etapas'][$etapa['codigo']]['vp_costo_impuestos'] =  (($etapa['costo_impuestos_p']));
            }else{
                $informacion['etapas'][$etapa['codigo']]['vp_costo_impuestos'] =  $valorAnteriorCi/pow(1+$descuentoMensual,$etapa['tiempo_fin']);
            }
            $valorAnteriorCi = $informacion['etapas'][$etapa['codigo']]['vp_costo_impuestos'];

            if($valorAnteriorCg == 0){
                $informacion['etapas'][$etapa['codigo']]['vp_costo_gastos'] =  (($etapa['costo_gastos_p']));
            }else{
                $informacion['etapas'][$etapa['codigo']]['vp_costo_gastos'] =  $valorAnteriorCg/pow(1+$descuentoMensual,$etapa['tiempo_fin']);
            }
            $valorAnteriorCg = $informacion['etapas'][$etapa['codigo']]['vp_costo_gastos'];

        }

        $informacion['etapas'] = array_reverse($informacion['etapas'],false);

        $preResultado = [];

        $fechaEtapa = (!empty($this->fecha_etapa_actual))?$this->fecha_etapa_actual:new Date();
        $hoy = new \DateTime('now');
        $diff = $fechaEtapa->diff($hoy);

        $datosEtapaActual = $informacion['etapas'][$etapaActual->code];

        if($dParuqeadero != ''){
            $datosEtapaActual['vp_costo_parqueadero'] = $dParuqeadero;
        }

        if($dComparendos != ''){
            $datosEtapaActual['vp_costo_comparendos'] = $dComparendos;
        }

        if($dInpuesto != ''){
            $datosEtapaActual['vp_costo_impuestos'] = $dInpuesto;
        }

        if($dGastos != ''){
            $datosEtapaActual['vp_costo_gastos'] = $dGastos;
        }

        $difMeses = $datosEtapaActual['tiempo']-($diff->days/(365/12));

        $difMeses = ($difMeses < 0)?0.5:$difMeses;
        $fechaAvaluo = new Date($fechaAvaluo);
        $diff = $fechaAvaluo->diff($hoy);

        $tiempoFinP = ($datosEtapaActual['tiempo_fin']+$difMeses) + ($diff->days/(365/12));

        $edadAvaluo = $fechaAvaluo->format('Y')-$this->modelo_automotor;

        if($datosEtapaActual['tiempo'] == 0){
            $datosEtapaActual['tiempo'] = 1;
        }

        $vpCostoParqueaderoF = $datosEtapaActual['vp_costo_parqueadero']+($datosEtapaActual['costo_parqueadero_etapa_p']
            *(11/$datosEtapaActual['tiempo']));

        $vpCostoComparendosF = $datosEtapaActual['vp_costo_comparendos'];

        $vpCostoImpuestosF = $datosEtapaActual['vp_costo_impuestos'];

        $vpCostoGastosF = $datosEtapaActual['vp_costo_gastos'];

        $vpCostoJurudisoF = $datosEtapaActual['vp_costo_juridico']+($datosEtapaActual['costo_mensual_p'] * $difMeses);


        if($valorAvaluo > 0){
            $valorUltimo = $valorAvaluo;
        }elseif(!empty($this->valor_avaluo)){
            $valorUltimo = $this->valor_avaluo;
        }else{
            $valorUltimo = $this->valor_desembolsado / $valorLtv;
        }


        $condiciones = Cache::read('conditions','long');
        $condicionesT = $condiciones[TypesConditionsTable::TASADESVALORIZACION];
        $desvalorizacion = ConditionsTable::getValorCondicion($condicionesT,$edadAvaluo);
        $desvalorizacionMensual = pow((1 + ($desvalorizacion / 100)), (1 / 12)) - 1;
        $valorGarantiaFin =  $valorUltimo*pow(1-$desvalorizacionMensual,$tiempoFinP);

        $valorRemate = 0;

        if($evaluador == 1){
            $valorRemate = $valorGarantiaFin * (1-$porcentajePerito);
        }elseif ($evaluador == 3){
            $valorRemate = ($valorGarantiaFin * (1-$porcentajePerito))*(1-$porcentajeMotor);
        }elseif ($evaluador == 2){
            $valorRemate = ($valorGarantiaFin * (1-$porcentajePerito))*(1-$porcentajeFasecolda);
        }

        #$vpRemate = $valorRemate / pow(1+$descuentoMensual,($datosEtapaActual['tiempo_fin']+$datosEtapaActual['tiempo']));

        $vpRemate = $valorRemate / pow(1+$descuentoMensual,($datosEtapaActual['tiempo_fin']+$difMeses));

        $constoSinCargar = 0;
        $vpGarantia = $vpRemate - ($vpCostoParqueaderoF+$vpCostoComparendosF+$vpCostoImpuestosF+$vpCostoGastosF+$vpCostoJurudisoF)-$constoSinCargar;

        if($this->company == 9) {
            $condicionesL = $condiciones[TypesConditionsTable::CONDONACIONCASTIGADA];
            $limiteCondonacion = ConditionsTable::getValorCondicion($condicionesL, $this->days_past_due);
        }else{
            $condicionesL = $condiciones[TypesConditionsTable::CONDONACIONVIGENTE];
            $limiteCondonacion = ConditionsTable::getValorCondicion($condicionesL, $this->days_past_due);
        }

        $vpConLimite = ($vpGarantia >= $this->total_debt)?$this->total_debt:max($vpGarantia,((1-($limiteCondonacion/100))*$this->saldo_contable));


        $preResultado['dias'] = $diff->days;
        $preResultado['dif_meses_ea'] = $difMeses;
        $preResultado['tiempo_fin_desde_avaluo'] = $tiempoFinP;
        $preResultado['edad_avaluo'] = $edadAvaluo;
        $preResultado['vp_costo_parqueadero_final'] = $vpCostoParqueaderoF;
        $preResultado['vp_costo_comparendos_final'] = $vpCostoComparendosF;
        $preResultado['vp_costo_impuestos_final'] = $vpCostoImpuestosF;
        $preResultado['vp_costo_gastos_final'] = $vpCostoGastosF;
        $preResultado['vp_costo_juridico_final'] = $vpCostoJurudisoF;
        $preResultado['valor_garantia_ultimo'] = $valorUltimo;
        $preResultado['valor_garantia_fin_proceso'] = $valorGarantiaFin;
        $preResultado['valor_remate'] = $valorRemate;
        $preResultado['vp_valor_remate'] = $vpRemate;
        $preResultado['vp_garantia'] = $vpGarantia;
        $preResultado['limite_condonacion'] = $limiteCondonacion;
        $preResultado['vp_garantia_limite'] = $vpConLimite;

        $cumpleAcierta = $this->politicaAcierta();

        $pagoTotal=($cumpleAcierta)?max(0,$vpConLimite):$this->total_debt;

        $v1 = 0;
        $v2 = 0;
        if($cumpleAcierta){
            $saldoContable = $this->saldo_contable;
            if(empty($this->saldo_contable)){
                $saldoContable = 1;
            }
            $v1 = ($cumpleAcierta)?($pagoTotal <= 0)?1:1-($vpConLimite/$this->total_debt):0;
            $v2 = ($cumpleAcierta)?($pagoTotal <= 0)?1:1-($vpConLimite/$saldoContable):0;
        }

        $condonacionTotal = max($v1*100,0)/100;
        $condonacionCapital = max($v2*100,0)/100;

        $interes = pow(1 + $this->rate/100,(1/12))-1;
        $pago = $this->pmt($interes,$this->plazo_inicial,$this->valor_desembolsado);

        $fechaDesembolso = $this->fecha_apertura;
        $diff = $fechaDesembolso->diff($hoy);

        $cuotasPagadas = floor(($diff->days-$this->days_past_due)/(365/12));

        $valorPagado = $this->fv($interes, $cuotasPagadas, -$pago);

        $fechaDesembolso = $this->fecha_apertura;
        $diff = $fechaDesembolso->diff($hoy);

        $edadCredito = floor(($diff->days)/(365/12));

        $tirFinal = pow(($pagoTotal+$valorPagado)/$this->valor_desembolsado,(12/$edadCredito))-1;

        $deltaTir = ($tirFinal*100) - $this->rate;

        $pagoTotalExperto = $this->saldo_contable*(1-$condonacionCapitalExperto);

        $resultado = [];
        $resultado['cuemple_acierta'] = $cumpleAcierta;
        $resultado['tasa_anual'] = $this->rate;
        $resultado['pago_total'] = $pagoTotal;
        $resultado['condonacion_total'] = $condonacionTotal;
        $resultado['valor_condonacion_total'] = $this->total_debt*$condonacionTotal;
        $resultado['condonacion_capital'] = $condonacionCapital;
        $resultado['interes'] = $interes;
        $resultado['pago'] = $pago;
        $resultado['cuotas_pagadas'] = $cuotasPagadas;
        $resultado['valor_pagado'] = $valorPagado;
        $resultado['edad_credito'] = $edadCredito;
        $resultado['tir_final'] = $tirFinal;
        $resultado['delta_tir'] = $deltaTir;

        $resultado['condonacion_total_experto'] = $condonacionCapitalExperto;
        $resultado['valor_condonacion_total_experto'] = $this->saldo_contable*$condonacionCapitalExperto;

        $resultado['pago_total_experto'] = $pagoTotalExperto;

        return $resultado;

    }

    /**
     * @param $interest
     * @param $num_of_payments
     * @param $PV
     * @param float $FV
     * @param int $Type
     * @return float|int
     */
    private function pmt($interest, $num_of_payments, $PV, $FV = 0.00, $Type = 0)
    {

        if($interest == 0){
            $cuota = $PV/$num_of_payments;
        }else{
            $xp = pow((1 + $interest), $num_of_payments);
            $cuota = ($PV * $interest * $xp / ($xp - 1) + $interest / ($xp - 1) * $FV) * ($Type == 0 ? 1 : 1 / ($interest + 1));
        }
        return $cuota;
    }

    /**
     * @param int $rate
     * @param int $nper
     * @param int $pmt
     * @param int $pv
     * @param int $type
     * @return bool|float|int
     */
    private function fv($rate = 0, $nper = 0, $pmt = 0, $pv = 0, $type = 0) {

        // Validate parameters
        if ($type != 0 && $type != 1) {
            return False;
        }

        // Calculate
        if ($rate != 0.0) {
            return -$pv * pow(1 + $rate, $nper) - $pmt * (1 + $rate * $type) * (pow(1 + $rate, $nper) - 1) / $rate;
        } else {
            return -$pv - $pmt * $nper;
        }
    }

    public function valorHpCastigada(){

        /** @var  $legalCodes LegalCode*/
        $legalCodes = TableRegistry::get('LegalCodes');
        $legalCodes = $legalCodes->find()
            ->where([
                'code' => $this->step
            ])
            ->first();

        $valor = $this->total_debt;

        //1 = no aplica
        //2 = promedio saldo total y saldo contable
        //3 = valor maximo saldo contable y valor liquidación
        // 06 leasing

        if($legalCodes){
            if($legalCodes->minimum_payment == 1){
                $valor = $this->total_debt;
            }elseif($legalCodes->minimum_payment == 2){
                $valor = ($this->total_debt + $this->saldo_contable)/2;
            }elseif($legalCodes->minimum_payment == 3){
                $valor = max($this->saldo_contable,$this->vr_liquidacion);
            }
        }

        return $valor;
    }

    public static function getCogigo($tipo, $subtipo = 1){

        $codigo = '';

        switch ($tipo){ //si todos requiere firma le coloca NORM de lo contrario NORVIR
            case 1:
                switch ($subtipo) {
                    case 0:
                        $codigo = 'NORVIR';//sin firma pagare=0
                        break;
                    default:
                        $codigo = 'NORM';//con firma pagare=1
                        break;
                }
                break;
            case 2:
                $codigo = 'REEST';
                break;
            case 3:
                switch ($subtipo){
                    case 0:
                        $codigo = 'REDOL'; //si campo retrenched police = 3
                        break;
                    default:
                        $codigo = 'RE'; //de lo contrario RE
                        break;
                }
                break;
            case 6:
                $codigo = 'REPCH'; 
                break;
            case 7:
                $codigo = 'PCMORA';
                break;
            case 8:
                $codigo = 'NPGI';
                break;
            case 9:
                $codigo = 'RC';
                break;
            case 10:
                $codigo = 'SDESE';
                break;
            case 11:
                $codigo = 'CPTCON';
                break;
            case 12:
                switch ($subtipo) {
                    case 0:
                        // $codigo = 'APCCOT' ;
                        $codigo = 'CIAPCC'; //sin firma pagare=0
                        break;
                    default:
                        $codigo = 'APCCC'; //con firma pagare=1
                        break;
                }
                break;
            case 13:
                $codigo = 'CPTCAS';
                break;
            case 14:
                $codigo = 'CPTCAS';
                break;
            case 15:
                $codigo = 'CPTVH';
                break;
        }

        return $codigo;

    }
}
