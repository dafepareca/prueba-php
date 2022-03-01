<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use ArrayObject;
use Cake\Event\Event;
use Cake\ORM\Entity;
use Cake\Core\Exception\Exception;
use Cake\Utility\Inflector;

/**
 * BeforeDelete behavior
 */
class BeforeDeleteBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'events' => [
            'Model.beforeDelete' => 'beforeDelete'
        ]
    ];

    /**
     * beforeDelete method
     * @param $event
     * @param $entity
     * @param $options
     * @throws Exception
     * @return boolean
     */
    public function beforeDelete(Event $event, Entity $entity, ArrayObject $options){
        $canDelete = true;
        foreach ($this->_table->associations()->type('HasMany') as $model => $details){
            if ($details->dependent() !== true ) {
                if ($details->getName() == $this->_table->getRegistryAlias()) {
                    $ModelInstance = $this->_table;
                }else{
                    $ModelInstance = $this->_table->{$details->getName()};
                }
                $count = $ModelInstance->find('all', ['conditions' => [$details->getForeignKey() => $entity->id]])->count();
                if ($count) {
                    throw new Exception ( sprintf(__("There are %s linked to this record."), strtolower(__($details->getName()))) );
                }
                if ($count > 0) {
                    $canDelete = false;
                }
            }
        }
        return $canDelete;
    }

    /**
     * archiveUserGeneral method
     * @param \Cake\Datasource\EntityInterface $entity The entity to update.
     *
     * @throws Exception
     * @return boolean
     */
    public function archiveUserGeneral($entity){
        $this->_table->updateAll(
            [
                'email' => '*'.$entity->email,
                'mobile' => '@'.$entity->mobile,
                'token' => null,
                'token_visible' => null,
                'user_status_id' => \App\Model\Table\UserStatusesTable::Archived,
                'modified' => date('Y-m-d H:i:s')
            ],[
                'id' => $entity->id
            ]
        );
        return true;
    }
}
