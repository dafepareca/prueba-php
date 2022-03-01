<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * CityOffices Model
 *
 * @property \App\Model\Table\OfficesTable|\Cake\ORM\Association\HasMany $Offices
 *
 * @method \App\Model\Entity\CityOffice get($primaryKey, $options = [])
 * @method \App\Model\Entity\CityOffice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\CityOffice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\CityOffice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\CityOffice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\CityOffice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\CityOffice findOrCreate($search, callable $callback = null, $options = [])
 */
class CityOfficesTable extends Table
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

        $this->setTable('city_offices');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Auditable');
        $this->addBehavior('BeforeDelete');

        $this->hasMany('Offices', [
            'foreignKey' => 'city_office_id'
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
            ->allowEmpty('name');

        return $validator;
    }
}
