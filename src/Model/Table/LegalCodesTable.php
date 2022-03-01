<?php
namespace App\Model\Table;

use App\Test\TestCase\Model\Table\ValueStagesTableTest;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LegalCodes Model
 *
 * @method \App\Model\Entity\LegalCode get($primaryKey, $options = [])
 * @method \App\Model\Entity\LegalCode newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LegalCode[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LegalCode|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LegalCode patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LegalCode[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LegalCode findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LegalCodesTable extends Table
{

    use CurrentUserTrait;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('legal_codes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('Auditable');

        $this->hasMany('ValueStages', [
            'foreignKey' => 'legal_codes_id'
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
            ->requirePresence('code', 'create')
            ->notEmpty('code');

        $validator
            ->allowEmpty('description');

        $validator
            ->boolean('apply_mortgage_credit')
            ->allowEmpty('apply_mortgage_credit');

        $validator
            ->boolean('apply_consumer_credit')
            ->allowEmpty('apply_consumer_credit');

        return $validator;
    }
}
