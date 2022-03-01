<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
use Cake\ORM\Entity;

use Cake\Datasource\RepositoryInterface;
use Cake\Datasource\EntityInterface;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;

use Cake\Filesystem\Folder;

/**
 * Attachments Model
 *
 * @method \App\Model\Entity\Attachment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Attachment newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Attachment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Attachment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Attachment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Attachment[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Attachment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AttachmentsTable extends Table
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

        $this->setTable('attachments');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'photo' => [
                'fields' => [
                    'dir' => 'file_dir',
                    'size' => 'file_size',
                    'type' => 'file_type',
                ],
                'nameCallback' => function (array $data, array $settings) {
                    $ext = substr(strrchr($data['name'], '.'), 1);
                    return 'original.' . $ext;
                },
                'filesystem' => [
                    'root' => ROOT.'/'.'webroot'.'/'.'img'.'/',
                ],
                'path' => '{model}/{field}/{field-value:foreign_key}/',
                'transformer' => function (RepositoryInterface $table, EntityInterface $entity, $data, $field, $settings) {
                    $extension = pathinfo($data['name'], PATHINFO_EXTENSION);
                    $sizes = [
                        'small' => [
                            'size' => new Box(50,50),
                            'mode' => ImageInterface::THUMBNAIL_OUTBOUND
                        ],
                        'medium' => [
                            'size' => new Box(100,100),
                            'mode' => ImageInterface::THUMBNAIL_OUTBOUND
                        ],
                        'large' => [
                            'size' => new Box(200,200),
                            'mode' => ImageInterface::THUMBNAIL_OUTBOUND
                        ]
                    ];
                    $imagine = new Imagine();
                    $open = $imagine->open($data['tmp_name']);
                    $data2 = [$data['tmp_name'] => $data['name']];
                    foreach($sizes as $size => $info){
                        $tmp = tempnam(sys_get_temp_dir(), 'upload') . '.' . $extension;
                        $data2[$tmp] = $size .'_' . $data['name'];
                        $open->thumbnail($info['size'], $info['mode'])->save($tmp);
                    }
                    return $data2;
                },
            ]
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
            ->requirePresence('model', 'create')
            ->notEmpty('model');

        $validator
            ->allowEmpty('photo')
            ->add('photo', 'validExtension', [
                'rule' => ['extension', ['gif', 'jpeg', 'png', 'jpg'] ],
                'message' => __('These files extension are allowed: .gif, .jpg, .jpeg, .png')
            ]);

        return $validator;
    }

    /**
     * Delete the files from storage and differ deletion by "section" of
     * entity (e.g. pictures have many files because of thumbnails etc.)
     *
     */
//    public function afterDelete(Event $event, Entity $entity, ArrayObject $options){
//        $folder = new Folder(WWW_ROOT.DS.'webroot'.DS.'img'.DS.$entity->file_dir);
//        if($folder->delete()){
//            echo "File deleted";
//        }else{
//            echo "Deletion failed!!";
//        }
//    }
}
