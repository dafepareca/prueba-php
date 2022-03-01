<?php
namespace App\Model\Table;

use App\Controller\Component\DaviviendaComponent;
use App\Model\Entity\LegalCode;
use App\Model\Entity\Obligation;
use App\Model\Entity\ProductCode;
use App\Model\Entity\TypeObligation;
use Cake\Cache\Cache;
use Cake\Core\App;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

/**
 * Obligations Model
 *
 * @property \App\Model\Table\CustomersTable|\Cake\ORM\Association\BelongsTo $Customers
 * @property \App\Model\Table\TypeObligationsTable|\Cake\ORM\Association\BelongsTo $TypeObligations
 * @property \App\Model\Table\TypeObligationsTable|\Cake\ORM\Association\BelongsTo $Charges
 *
 * @method \App\Model\Entity\Obligation get($primaryKey, $options = [])
 * @method \App\Model\Entity\Obligation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Obligation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Obligation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Obligation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Obligation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Obligation findOrCreate($search, callable $callback = null, $options = [])
 */
class ObligationsTable extends Table
{

    const NORMALIZAR = 1;
    const ACPK = 12;
    var $rateTdc = 0;
    var $rateUvr = [];
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        // $this->rateTdc = $this->annualEffectiveRateTdc();

        $this->rateUvr = self::annualEffectiveRateUvr();

        $options = [
            // Refer to php.net fgetcsv for more information
            'length' => 0,
            'delimiter' => ',',
            'enclosure' => '"',
            'escape' => '\\',
            // Generates a Model.field headings row from the csv file
            'headers' => true,
            // If true, String $content is the data, not a path to the file
            'text' => false,
        ];

        $this->addBehavior('Csv', $options);

        $this->setTable('obligations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Customers', [
            'foreignKey' => 'customer_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('TypeObligations', [
            'foreignKey' => 'type_obligation_id'
        ]);
        $this->belongsTo('Charges', [
            'foreignKey' => 'charge_id'
        ]);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('obligation', 'create')
            ->notEmpty('obligation');

        $validator
            ->allowEmpty('total_debt');

        $validator
            ->allowEmpty('fee');

        $validator
            ->allowEmpty('minimum_payment');

        $validator
            ->allowEmpty('currency');

        $validator
            ->allowEmpty('rate');

        $validator
            ->integer('days_past_due')
            ->allowEmpty('days_past_due');

        $validator
            ->boolean('restructuring')
            ->allowEmpty('restructuring');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['customer_id'], 'Customers'));
        $rules->add($rules->existsIn(['type_obligation_id'], 'TypeObligations'));

        return $rules;
    }

    public function findAll(Query $query, array $options)
    {

        return $query->formatResults(function (\Cake\Collection\CollectionInterface $results) {
            return $results->map(function ($row) {
                /** @var  $row Obligation*/

                // $row->restructuring = 0;

               // $row->maskobligation = substr($row->obligation, 0, 4) . '*****' . substr($row->obligation, -4);
                $row->maskobligation = $row->obligation;

                if($row->type_obligation_id == TypeObligationsTable::VEH && $this->withoutGarment($row)){
                    $row->type_obligation_id = TypeObligationsTable::CXF;
                }

                if($row->type_obligation_id == TypeObligationsTable::HIP && $row->currency == 'UVR'){
                    
                    $tipoProducto = TableRegistry::get('TypeObligations');
                    $tipoProducto = $tipoProducto->find()
                        ->where([
                            'id' => TypeObligationsTable::HIP_UVR
                        ])
                        ->first();

                    $row->type_obligation->term = $tipoProducto->term;

                }
                if($row->type_obligation_id == TypeObligationsTable::HIP && $this->withoutGarment($row)){
                    /** @var  $tipoProducto TypeObligation*/

                    $tipoProducto = TableRegistry::get('TypeObligations');
                    $tipoProducto = $tipoProducto->find()
                        ->where([
                            'id' => TypeObligationsTable::HIP_PRE
                        ])
                        ->first();

                    $row->type_obligation->term = $tipoProducto->term;
                }

                if($row->company == 9){
                    $row->punished = true;
                    $fecha = (!empty($row->punishment_date))?$row->punishment_date->format('Y-m-d'):date('Y-m-d');
                    $row->time_punished = $this->timePunished($fecha);
                }

                if($row->type_obligation_id == TypeObligationsTable::TDC){

                    // $row->rate = $this->rateTdc;
                    $tasaMensual = pow((1 + ($row->rate / 100)), (1 / 12)) - 1;
                    if($row->maxRediferido == null){
                        $plazoMaximo = $row->type_obligation->term;
                    }else{
                        $plazoMximo = $row->maxRediferido;
                    }
                    $row->fee = ($row->total_debt/$plazoMximo)+($row->total_debt*$tasaMensual);
                    /*
                     * Cuota pactada= (saldo total/36)+( saldo total*tasa de interés tarjeta)
                     *
                    if((int)$row->minimum_payment > 0){
                        $row->fee = round(($row->minimum_payment/(1+($row->days_past_due/30))));
                    }else{
                        $row->fee = (int)round($row->approved_quota/36);
                    }
                    */
                }

                if($row->type_obligation_id == TypeObligationsTable::SOB){

                    // $row->rate = $this->rateTdc;

                    if((int)$row->total_debt > 0){
                        $row->fee = $row->total_debt/36;
                    }else{
                        $row->fee = 0;
                    }
                }


                if($row->type_obligation_id == TypeObligationsTable::CXR){
                    if((int)$row->total_debt > 0){
                        $row->fee = $row->fee;
                    } else{
                        $row->fee = 0;
                    }
                    /*if((int)$row->minimum_payment > 0){
                        $row->fee = round(($row->minimum_payment/(1+($row->days_past_due/30))));
                    }else{
                        $row->fee = (int)round($row->approved_quota/36);
                    }*/
                }


                if($this->restriccion($row)){
                    $row->sin_cambio = 1;
                    $row->restriccion = 1;
                }

                if($this->restriccionJuridica($row)){
                    $row->restriccionJuridica = 1;
                    $row->restriccion = 1;
                }

                if($row->total_debt == 0){
                    $row->restriccion = 1;
                }

                 $row->tasaMensual = $this->calcularTasaMesual($row);
                # $row->estrategia = $this->definirEstrategia($row);

                 return $row;
            });
        });
    }

    public function calcularTasaMesual($obligacion){

        $tasaMensual = pow((1 + ($obligacion->rate/100)),(1/12)) - 1;
        return round($tasaMensual*100,2);

    }

    public function definirEstrategia($obligacion){
        /** @var  $obligacion Obligation*/
        $tipo = $obligacion->type_obligation_id;
        $diasMora = $obligacion->days_past_due;

        if(in_array($obligacion->company,[/*5,*/9])){
           return 4;
        }elseif($diasMora > 75){
           return 2;
        }elseif(($tipo == 'TDC' || $tipo == 'CXR') &&  $diasMora > 0){
            return 3;
        }elseif($diasMora >= 0){
            return 2;
        }else{
            return 0;
        }
    }

    /**
     * @param $obligacion
     * @return bool
     */
    public function restriccionJuridica($obligacion){
        /** @var  $obligacion Obligation*/

        $consumer = [
            TypeObligationsTable::TDC,
            TypeObligationsTable::CXF,
            TypeObligationsTable::CXR,
            TypeObligationsTable::VEH,
            TypeObligationsTable::SOB
        ];


        if(!empty($obligacion->step)){

            $legalCodes = TableRegistry::get('LegalCodes');
            /** @var  $code LegalCode*/
            $code = $legalCodes->find()->where(['code'=>$obligacion->step])->first();

            if($code){
                if(in_array($obligacion->type_obligation_id,[TypeObligationsTable::HIP]) && $code->apply_mortgage_credit){
                    return true;
                }elseif (in_array($obligacion->type_obligation_id,$consumer) && $code->apply_consumer_credit){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $obligacion
     * @return bool
     */
    public function restriccion($obligacion){

        /** @var  $obligacion Obligation*/
        /*if(($obligacion->type_obligation_id == TypeObligationsTable::VEH || $obligacion->type_obligation_id == TypeObligationsTable::HIP) && $obligacion->company == 9){
        if(($obligacion->type_obligation_id == TypeObligationsTable::VEH) && $obligacion->company == 9){
            return true;
        }else*/
        // if(in_array($obligacion->company,[5])){
        //     return true;
        // }else
        if (!empty($obligacion->mcc_product)){

            /** @var  $productCodes ProductCode*/
            $productCodes = TableRegistry::get('ProductCodes');
            $productCodes = $productCodes->find()
                ->where([
                    'code' => $obligacion->mcc_product
                ])
                ->first();
            if($productCodes){
                if($productCodes->exclud_offer){
                    return true;
                }
            }
        }
        return false;
    }


    public function annualEffectiveRateTdc(){

        $annualEffectiveRateTdc = TableRegistry::get('AnnualEffectiveRateTdc');

        $rateTdc = $annualEffectiveRateTdc->find()
            ->where([
                'MONTH(fecha)' => date('m'),
                'year(fecha)' => date('Y')
            ])
            ->first();

        if($rateTdc){
            return $rateTdc->rate;
        }else{

            $rateTdc = $annualEffectiveRateTdc->find()
                ->where([
                    'MONTH(fecha)' => date("m", mktime(0, 0, 0, date("m")-1,date("d"),date("Y"))),
                    'year(fecha)' => date("Y", mktime(0, 0, 0, date("m")-1,date("d"),date("Y")))
                ])
                ->first();

            return $rateTdc->rate;
        }

    }

    static function annualEffectiveRateUvr(){

        $annualEffectiveRateUvr = TableRegistry::get('AnnualEffectiveRateUvr');

        $rateUvr = $annualEffectiveRateUvr->find()
            ->where([
                'MONTH(month_date)' => date('m'),
                'year(month_date)' => date('Y')
            ])
            ->first();

        if($rateUvr){
            return [
                'rate' => $rateUvr->rate,
                'value' => $rateUvr->value
            ];
        }else{

            $rateUvr = $annualEffectiveRateUvr->find()
                ->where([
                    'MONTH(month_date)' => date("m", mktime(0, 0, 0, date("m")-1,date("d"),date("Y"))),
                    'year(month_date)' => date("Y", mktime(0, 0, 0, date("m")-1,date("d"),date("Y")))
                ])
                ->first();

            return [
                'rate' => $rateUvr->rate,
                'value' => $rateUvr->value
            ];
        }
    }

    /**
     * @param $obligacion
     * @return bool
     */
    public function withoutGarment($obligacion){
        /** @var  $obligacion Obligation*/
       if (!empty($obligacion->mcc_product)){

            /** @var  $productCodes ProductCode*/
            $productCodes = TableRegistry::get('ProductCodes');
            $productCodes = $productCodes->find()
                ->where([
                    'code' => $obligacion->mcc_product
                ])
                ->first();
            if($productCodes){
                if($productCodes->without_garment){
                    return true;
                }
            }
        }
        return false;
    }

    private function timePunished($datePunished) {

        $fechainicial = new \DateTime($datePunished);
        $fechafinal = new \DateTime('now');

        $diferencia = $fechainicial->diff($fechafinal);

        $meses = ( $diferencia->y * 12 ) + $diferencia->m;
        return ceil($meses);
    }

}
