<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TypeObligations Model
 *
 * @property \App\Model\Table\ObligationsTable|\Cake\ORM\Association\HasMany $Obligations
 *
 * @method \App\Model\Entity\TypeObligation get($primaryKey, $options = [])
 * @method \App\Model\Entity\TypeObligation newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TypeObligation[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TypeObligation|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TypeObligation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TypeObligation[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TypeObligation findOrCreate($search, callable $callback = null, $options = [])
 */
class TypeObligationsTable extends Table
{

    use CurrentUserTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */

    const TDC = 1;
    const CXR = 2;
    const CXF = 3;
    const VEH = 4;
    const HIP = 5;
    const HIP_UVR = 6;
    const SOB = 7;
    const HIP_PRE = 8;

    public function initialize(array $config)
    {

        parent::initialize($config);

        $this->addBehavior('Auditable');
        $this->addBehavior('BeforeDelete');

        $this->setTable('type_obligations');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Obligations', [
            'foreignKey' => 'type_obligation_id'
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
            ->requirePresence('type', 'create')
            ->notEmpty('type');

        $validator
            ->requirePresence('term', 'create')
            ->notEmpty('description');


        return $validator;
    }
}
