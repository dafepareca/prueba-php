<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\CurrentUserTrait;

/**
 * SettingCategories Model
 *
 * @property \Cake\ORM\Association\HasMany $Settings
 *
 * @method \App\Model\Entity\SettingCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\SettingCategory newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SettingCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SettingCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SettingCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SettingCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SettingCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SettingCategoriesTable extends Table
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

        $this->setTable('setting_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->addBehavior('BeforeDelete');
        $this->addBehavior('Auditable');

        $this->hasMany('Settings', [
            'foreignKey' => 'setting_category_id'
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

        $validator
            ->allowEmpty('description');

        return $validator;
    }
}
