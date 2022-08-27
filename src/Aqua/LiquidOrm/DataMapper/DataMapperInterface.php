<?php

declare(strict_types=1);

namespace Aqua\LiquidOrm\DataMapper;

interface DataMapperInterface
{

    /**
     * prepare the query string
     *
     * @param string $sqlQuery
     * @return self
     */
    public function prepare(string $sqlQuery): self;


    /**
     *
     */
    public function bind($value);


    /**
     *
     */
    public function bindParameters(array $fields, bool $isSearch = false);

    /**
     *
     */
    public function numRows(): int;


    /**
     *
     */
    public function execute(): void;


    /**
     *
     */
    public function result(): object;


    /**
     *
     */
    public function results(): array;


    /**
     *
     */
    public function getLastId() : int;


}


