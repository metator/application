<?php
namespace Metator\Address;

class DataMapperTest extends \PHPUnit_Framework_TestCase
{
    function setUp()
    {
        $this->db = \phpunit_bootstrap::getServiceManager()->get('Zend\Db\Adapter\Adapter');
        $this->db->getDriver()->getConnection()->beginTransaction();
    }

    function tearDown()
    {
        $this->db->getDriver()->getConnection()->rollback();
    }

    function testShouldCreateNewAddress()
    {
        $address = array(
            'first_name' => 'Joshua',
            'last_name' => 'Ribakoff',
            'email' => 'josh.ribakoff@gmail.com',
            'address' => '123 Test St',
            'address2' => 'Suite 5',
            'city' => 'Port St Lucie',
            'state' => 'FL',
            'postal' => '00123',
            'country' => 'USA',
            'phone' => '0101010101',
            'fax' => '0202020202',
        );

        $addressMapper = new DataMapper($this->db);
        $id = $addressMapper->save($address);

        $newAddress = $addressMapper->load($id);
        $this->assertSame(array('id'=>$id)+$address, $newAddress, 'should save new address');
    }
}