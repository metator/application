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

        $loadedAddress = $addressMapper->load($id);
        $this->assertSame(array('id'=>$id)+$address, $loadedAddress, 'should save new address');
    }

    function testShouldUpdateAddress()
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

        $updatedAddress = array(
            'id' => $id,
            'first_name' => 'Joshua-updated',
            'last_name' => 'Ribakoff-updated',
            'email' => 'josh.ribakoff-updated@gmail.com',
            'address' => '123 Test St-updated',
            'address2' => 'Suite 5-updated',
            'city' => 'Port St Lucie-updated',
            'state' => 'FL-updated',
            'postal' => '12345',
            'country' => 'USA-updated',
            'phone' => '111111111',
            'fax' => '2222222222',
        );

        $addressMapper = new DataMapper($this->db);
        $id = $addressMapper->save($updatedAddress);

        $loadedAddress = $addressMapper->load($id);
        $this->assertSame($updatedAddress, $loadedAddress, 'should save new address');
    }
}