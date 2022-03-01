<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HistoryStatuses Model
 *
 * @property \App\Model\Table\HistoryCustomersTable|\Cake\ORM\Association\HasMany $HistoryCustomers
 *
 * @method \App\Model\Entity\HistoryStatus get($primaryKey, $options = [])
 * @method \App\Model\Entity\HistoryStatus newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HistoryStatus[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HistoryStatus|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HistoryStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HistoryStatus findOrCreate($search, callable $callback = null, $options = [])
 */
class HistoryStatusesTable extends Table
{

    const PENDIENTE = 1;
    const COMITE = 2;
    const ACEPTADA = 3;
    const RECHAZADA = 4;
    const ACEPTADA_COMITE = 5;
    const RECHAZADA_COMITE = 6;
    const CONSULTA = 7;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('history_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('HistoryCustomers', [
            'foreignKey' => 'history_status_id'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        return $validator;
    }
}
