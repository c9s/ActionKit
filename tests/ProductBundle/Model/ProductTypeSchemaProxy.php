<?php
namespace ProductBundle\Model;
use LazyRecord\Schema\RuntimeSchema;
use LazyRecord\Schema\RuntimeColumn;
use LazyRecord\Schema\Relationship;
class ProductTypeSchemaProxy
    extends RuntimeSchema
{
    const schema_class = 'ProductBundle\\Model\\ProductTypeSchema';
    const model_name = 'ProductType';
    const model_namespace = 'ProductBundle\\Model';
    const COLLECTION_CLASS = 'ProductBundle\\Model\\ProductTypeCollection';
    const MODEL_CLASS = 'ProductBundle\\Model\\ProductType';
    const PRIMARY_KEY = 'id';
    const TABLE = 'product_types';
    const LABEL = '產品類型';
    public static $column_hash = array (
      'id' => 1,
      'product_id' => 1,
      'name' => 1,
      'quantity' => 1,
      'comment' => 1,
    );
    public static $mixin_classes = array (
    );
    public $columnNames = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'name',
      3 => 'quantity',
      4 => 'comment',
    );
    public $primaryKey = 'id';
    public $columnNamesIncludeVirtual = array (
      0 => 'id',
      1 => 'product_id',
      2 => 'name',
      3 => 'quantity',
      4 => 'comment',
    );
    public $label = '產品類型';
    public $readSourceId = 'default';
    public $writeSourceId = 'default';
    public $relations;
    public function __construct()
    {
        $this->relations = array( 
      'product' => \LazyRecord\Schema\Relationship::__set_state(array( 
      'data' => array( 
          'type' => 3,
          'self_schema' => 'ProductBundle\\Model\\ProductTypeSchema',
          'self_column' => 'product_id',
          'foreign_schema' => 'ProductBundle\\Model\\ProductSchema',
          'foreign_column' => 'id',
        ),
      'accessor' => 'product',
      'where' => NULL,
      'orderBy' => array( 
        ),
    )),
    );
        $this->columns[ 'id' ] = new RuntimeColumn('id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'autoIncrement' => true,
        ),
      'name' => 'id',
      'primary' => true,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => true,
      'enum' => NULL,
      'set' => NULL,
      'autoIncrement' => true,
    ));
        $this->columns[ 'product_id' ] = new RuntimeColumn('product_id',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => '產品',
          'renderAs' => 'SelectInput',
          'widgetAttributes' => array( 
            ),
          'refer' => 'ProductBundle\\Model\\ProductSchema',
        ),
      'name' => 'product_id',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => '產品',
      'renderAs' => 'SelectInput',
      'widgetAttributes' => array( 
        ),
      'refer' => 'ProductBundle\\Model\\ProductSchema',
    ));
        $this->columns[ 'name' ] = new RuntimeColumn('name',array( 
      'locales' => NULL,
      'attributes' => array( 
          'length' => 120,
          'required' => true,
          'label' => '類型名稱',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
              'size' => 20,
              'placeholder' => '如: 綠色, 黑色, 羊毛, 大、中、小等等。',
            ),
        ),
      'name' => 'name',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'varchar',
      'isa' => 'str',
      'notNull' => true,
      'enum' => NULL,
      'set' => NULL,
      'length' => 120,
      'required' => true,
      'label' => '類型名稱',
      'renderAs' => 'TextInput',
      'widgetAttributes' => array( 
          'size' => 20,
          'placeholder' => '如: 綠色, 黑色, 羊毛, 大、中、小等等。',
        ),
    ));
        $this->columns[ 'quantity' ] = new RuntimeColumn('quantity',array( 
      'locales' => NULL,
      'attributes' => array( 
          'default' => 0,
          'label' => '數量',
          'renderAs' => 'TextInput',
          'widgetAttributes' => array( 
            ),
          'validValues' => array( 
              -1,
              0,
              1,
              2,
              3,
              4,
              5,
              6,
              7,
              8,
              9,
              10,
              11,
              12,
              13,
              14,
              15,
              16,
              17,
              18,
              19,
              20,
              21,
              22,
              23,
              24,
              25,
              26,
              27,
              28,
              29,
              30,
              31,
              32,
              33,
              34,
              35,
              36,
              37,
              38,
              39,
              40,
              41,
              42,
              43,
              44,
              45,
              46,
              47,
              48,
              49,
              50,
              51,
              52,
              53,
              54,
              55,
              56,
              57,
              58,
              59,
              60,
              61,
              62,
              63,
              64,
              65,
              66,
              67,
              68,
              69,
              70,
              71,
              72,
              73,
              74,
              75,
              76,
              77,
              78,
              79,
              80,
              81,
              82,
              83,
              84,
              85,
              86,
              87,
              88,
              89,
              90,
              91,
              92,
              93,
              94,
              95,
              96,
              97,
              98,
              99,
              100,
            ),
        ),
      'name' => 'quantity',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'int',
      'isa' => 'int',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'default' => 0,
      'label' => '數量',
      'renderAs' => 'TextInput',
      'widgetAttributes' => array( 
        ),
      'validValues' => array( 
          -1,
          0,
          1,
          2,
          3,
          4,
          5,
          6,
          7,
          8,
          9,
          10,
          11,
          12,
          13,
          14,
          15,
          16,
          17,
          18,
          19,
          20,
          21,
          22,
          23,
          24,
          25,
          26,
          27,
          28,
          29,
          30,
          31,
          32,
          33,
          34,
          35,
          36,
          37,
          38,
          39,
          40,
          41,
          42,
          43,
          44,
          45,
          46,
          47,
          48,
          49,
          50,
          51,
          52,
          53,
          54,
          55,
          56,
          57,
          58,
          59,
          60,
          61,
          62,
          63,
          64,
          65,
          66,
          67,
          68,
          69,
          70,
          71,
          72,
          73,
          74,
          75,
          76,
          77,
          78,
          79,
          80,
          81,
          82,
          83,
          84,
          85,
          86,
          87,
          88,
          89,
          90,
          91,
          92,
          93,
          94,
          95,
          96,
          97,
          98,
          99,
          100,
        ),
    ));
        $this->columns[ 'comment' ] = new RuntimeColumn('comment',array( 
      'locales' => NULL,
      'attributes' => array( 
          'label' => '備註',
          'renderAs' => 'TextareaInput',
          'widgetAttributes' => array( 
            ),
        ),
      'name' => 'comment',
      'primary' => NULL,
      'unsigned' => NULL,
      'type' => 'text',
      'isa' => 'str',
      'notNull' => NULL,
      'enum' => NULL,
      'set' => NULL,
      'label' => '備註',
      'renderAs' => 'TextareaInput',
      'widgetAttributes' => array( 
        ),
    ));
    }
}
