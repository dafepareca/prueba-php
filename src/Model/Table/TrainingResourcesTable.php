<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TrainingResources Model
 *
 * @property \App\Model\Table\TrainingsTable|\Cake\ORM\Association\BelongsTo $Trainings
 *
 * @method \App\Model\Entity\TrainingResource get($primaryKey, $options = [])
 * @method \App\Model\Entity\TrainingResource newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TrainingResource[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TrainingResource|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TrainingResource patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TrainingResource[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TrainingResource findOrCreate($search, callable $callback = null, $options = [])
 */
class TrainingResourcesTable extends Table
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

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'resource' => [
                'fields' => [
                    'dir' => 'file_dir',
                    'size' => 'file_size',
                    'type' => 'file_type',
                ],
                /*'nameCallback' => function (array $data, array $settings) {
                    $ext = substr(strrchr($data['name'], '.'), 1);
                    return  time().rand(1,10).'.' . $ext;
                },*/
                'filesystem' => [
                    'root' => ROOT.'/'.'webroot'.'/'.'img'.'/',
                ],
                'path' => '{model}/{field}/{field-value:training_id}/',
            ]
        ]);

        $this->setTable('training_resources');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Trainings', [
            'foreignKey' => 'training_id',
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

        $validator
            ->allowEmpty('resource');

        $validator
            ->allowEmpty('file_size');

        $validator
            ->allowEmpty('file_type');

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
        $rules->add($rules->existsIn(['training_id'], 'Trainings'));

        return $rules;
    }
}
