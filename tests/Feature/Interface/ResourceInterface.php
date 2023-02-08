<?php

namespace Tests\Feature\Interface;

interface ResourceInterface
{
    public function testIndexNotAccess();

    public function testIndexPagination();

    public function testIndexCollection();

    public function testGetOne();

    public function testGetOneNotAccess();

    public function testStore();

    public function testStoreNotAccess();

    public function testUpdate();

    public function testUpdateNotAccess();

    public function testDestroy();

    public function testDestroyNotAccess();
}
