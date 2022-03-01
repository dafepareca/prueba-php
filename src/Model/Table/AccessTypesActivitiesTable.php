<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * AccessTypesActivities Model
 *
 * @method \App\Model\Entity\AccessTypesActivity get($primaryKey, $options = [])
 * @method \App\Model\Entity\AccessTypesActivity newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AccessTypesActivity[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AccessTypesActivity|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AccessTypesActivity patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AccessTypesActivity[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AccessTypesActivity findOrCreate($search, callable $callback = null, $options = [])
 */
class AccessTypesActivitiesTable extends Table
{
    use CurrentUserTrait;

    const CREATE    = 1;
    const VIEW      = 2;
    const UPDATE    = 3;
    const DELETE    = 4;
    const UPLOAD    = 5;
    const DOWNLOAD  = 6;
    const ARCHIVE   = 7;
    const PROFILE   = 8;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('access_types_activities');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');
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
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        return $validator;
    }
}
