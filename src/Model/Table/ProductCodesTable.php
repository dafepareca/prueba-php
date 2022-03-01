<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProductCodes Model
 *
 * @method \App\Model\Entity\ProductCode get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProductCode newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProductCode[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProductCode|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProductCode patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProductCode[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProductCode findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductCodesTable extends Table
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

        $this->setTable('product_codes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->requirePresence('code', 'create')
            ->notEmpty('code');

        $validator
            ->allowEmpty('description');

        $validator
            ->boolean('exclud_offer')
            ->allowEmpty('exclud_offer');

        return $validator;
    }
}
