<?php

namespace VoyagerDataTransport\Test;

use VoyagerDataTransport\Console\Commands\Traits\VoyagerDataController;

class ConfigGeneratorTest extends \PHPUnit\Framework\TestCase {

    const ROUTE_GET = 'get';
    const ROUTE_POST = 'post';

    const PERMISSION_PRE_IMPORT = 'browse_import_';
    const PERMISSION_PRE_EXPORT = 'browse_export_';

    private $_tableName = 'posts';

    private $_importPre = 'Import';
    private $_exportPre = 'Export';

    private $_urlImportPre = '/import_';
    private $_urlExportPre = '/export_';

    private $_aliasImportPre = 'voyager.browse_import_';
    private $_aliasExportPre = 'voyager.browse_export_';

    /**
     * Test import and export permission config data set.
     *
     * @return void
     */
    public function test_set_permission_config_content(): void
    {
        $_tableName = $this->_tableName;

        $_func = function ($_pre, $_table) {
            return "{$_pre}{$_table}";
        };

        $_permissionPre = [
            self::PERMISSION_PRE_IMPORT,
            self::PERMISSION_PRE_EXPORT,
        ];

        foreach ($_permissionPre as $_pre) {
            $_permissionConfig[] = $_func($_pre, $_tableName);
        }

        $expectedCounter = count($_permissionPre);
        $actualCounter = count($_permissionConfig);

        $expectedConfig = [
            self::PERMISSION_PRE_IMPORT . $_tableName,
            self::PERMISSION_PRE_EXPORT . $_tableName,
        ];

        $this->assertIsArray($_permissionConfig);
        $this->assertEquals($expectedCounter, $actualCounter);
        $this->assertEquals($expectedConfig, $_permissionConfig);

    }

    /**
     * Test route config data set.
     *
     * @return void
     */
    public function test_set_route_config_content()
    {
        $_tableName = $this->_tableName;

        $_routeMappings = [
            self::ROUTE_GET => $this->_getMapping($_tableName),
            self::ROUTE_POST => $this->_postMapping($_tableName),
        ];

        $_routeConfig = [];

        foreach ($_routeMappings as $_verb => $_mappings) {
            foreach ($_mappings as $_mKey => $_functions) {
                foreach ($_functions as $_fKey => $_function) {
                    $_routeConfig[$_verb][$_mKey][$_fKey] = $_function() ;
                }
            }
        }

        $this->assertIsArray($_routeConfig);

        $this->assertTrue( array_key_exists ('get', $_routeConfig) );
        $this->assertTrue( array_key_exists ('post', $_routeConfig) );

        $_contentKeys = [
            'url',
            'controllerName',
            'actionName',
            'alias',
        ];

        $_keyAndValueExist = function ( string $_key, array $_dataSet ): void {
            $_isValid = isset($_dataSet[$_key]) && !empty($_dataSet[$_key]);
            $this->assertTrue( $_isValid );
        };

        $_isValueString = function (string $_key, array $_dataSet): void {
            $this->assertIsString( $_dataSet[$_key] );
        };

        $_getTestResult = function (array $dataSet) use ($_contentKeys, $_keyAndValueExist, $_isValueString): void {

            foreach ($_contentKeys as $contentKey) {
                $_keyAndValueExist ( $contentKey, $dataSet );
                $_isValueString ( $contentKey, $dataSet );
            }

            $this->assertEquals( count ( $_contentKeys ), count ( $dataSet ) );
        };

        $_loopTest = function ( array $dataSet ) use ( $_getTestResult ): void {
            $counter = count( $dataSet );

            while ( $counter > 0 ) {
                $currentData = current($dataSet);
                $_getTestResult($currentData);
                next($dataSet);
                $counter--;
            }
            reset($dataSet);
        };

        $this->assertEquals(2, count ( $_routeConfig['get'] ) );
        $_loopTest($_routeConfig['get']);

        $this->assertEquals(1, count ( $_routeConfig['post'] ) );
        $_loopTest($_routeConfig['post']);
    }

    /**
     * Test route config set replace stub.
     *
     * @return void
     */
    public function test_set_replace_stub_route() {

        $_tableName = $this->_tableName;

        $_routeMappings = [
            'get' => $this->_getMapping($_tableName),
            'post' => $this->_postMapping($_tableName),
        ];

        $config = [];

        foreach ($_routeMappings as $_verb => $_mappings) {
            foreach ($_mappings as $_mKey => $_functions) {
                foreach ($_functions as $_fKey => $_function) {
                    $config[$_verb][$_mKey][$_fKey] = $_function() ;
                }
            }
        }

        $recordArr = array_merge($config['get'], $config['post']);

        $search = [];
        $replace = [];

        foreach ($recordArr as $key => $records) {
            foreach ($records as $pre => $record) {
                $search[] = $this->_getSearchValue($key + 1, "{$pre}_");
                $replace[] = $record;
            }
        }

        $this->assertIsArray($recordArr);
        $this->assertIsArray($search);
        $this->assertIsArray($replace);
        $this->assertEquals(count($search), count($replace));
    }

    /**
     * Get search key.
     *
     * @param  int  $key 
     * @param  string  $pre
     * @return string
     */
    private function _getSearchValue (int $key, string $pre): string {
        return "{{ {$pre}{$key} }}";
    }

    /**
     * Create [GET] route config data stub.
     *
     * @param  string  $tableName 
     * @return array
     */
    private function _getMapping (string $tableName): array
    {
        $_mapping = [
            [
                'url' => function () use ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                'actionName' => function () {
                     return 'index';
                },
                'alias' => function () use ($tableName) {
                    return "{$this->_aliasImportPre}{$tableName}";
                },
            ],
            [
                'url' => function () use ($tableName) {
                    return "{$this->_urlExportPre}{$tableName}";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_exportPre, $tableName);
                },
                'actionName' => function () {
                    return 'export';
                },
                'alias' => function () use ($tableName) {
                    return "{$this->_aliasExportPre}{$tableName}";
                },
            ],
        ];

        return $_mapping;
    }


    /**
     * Create [POST] route config data stub.
     *
     * @return array
     */
    private function _postMapping (string $tableName): array
    {
        $_mapping  = [
            [
                'url' => function () use ($tableName) {
                    return "{$this->_urlImportPre}{$tableName}/upload";
                },
                'controllerName' => function () use ($tableName) {
                    return $this->_controllerNameGenerate($this->_importPre, $tableName);
                },
                'actionName' => function () {
                    return 'upload';
                },
                'alias' => function () use ($tableName) {
                    return "{$this->_aliasImportPre}{$tableName}.upload";
                },
            ],
        ];

        return $_mapping;
    }

    /**
     * Get controller name.
     *
     * @param  string  $_namePre    Added to data table name before
     * @param  string  $_tableName  This data table name
     * @return string
     */    
    private function _controllerNameGenerate (string $_namePre, string $_tableName): string 
    {
        $tableName = strtolower($_tableName);
        $tempArr = explode("_", $tableName);
        $baseName = '';

        foreach ($tempArr as $s) {
            $baseName .= ucfirst($s);
        }

        return "{$_namePre}{$baseName}";
    }

}