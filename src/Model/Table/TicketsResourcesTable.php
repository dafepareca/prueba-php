<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TicketsResources Model
 *
 * @property \App\Model\Table\TicketsTable|\Cake\ORM\Association\BelongsTo $Tickets
 *
 * @method \App\Model\Entity\TicketsResource get($primaryKey, $options = [])
 * @method \App\Model\Entity\TicketsResource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TicketsResource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TicketsResource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TicketsResource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TicketsResource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TicketsResource findOrCreate($search, callable $callback = null, $options = [])
 */
class TicketsResourcesTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('tickets_resources');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'img' => [
                'fields' => [
                    'dir' => 'file_dir',
                    'size' => 'file_size',
                    'type' => 'file_type',
                ],
                'nameCallback' => function (array $data, array $settings) {
                    $ext = substr(strrchr($data['name'], '.'), 1);
                    return  time().rand(1,10).'.' . $ext;
                },
                'filesystem' => [
                    'root' => ROOT.'/'.'webroot'.'/'.'img'.'/',
                ],
                'path' => '{model}/{field}/{field-value:ticket_id}/',
            ]
        ]);

        $this->belongsTo('Tickets', [
            'foreignKey' => 'ticket_id',
            'joinType' => 'INNER'
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
        $rules->add($rules->existsIn(['ticket_id'], 'Tickets'));

        return $rules;
    }
}
