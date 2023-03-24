<?php

namespace Fab\Classes;

use Fab\Classes\Entity;

/**
 * EntityCollection
 * 
 * Défini un objet itérable pour stocker et manipuler une collection d'entité
 */
class EntityCollection implements \Iterator, \Countable, \JsonSerializable  {

    /**
     * @var array current collection of forms
     */
    private $entities;


    /**
     * Constructeur
     */
    public function __construct() {
        $this->entities = [];
    }

    /**
     * Ajoute une forme à la collection
     *
     * @param Form2d $form
     * 
     * @return self
     * 
     */
    public function add(Entity $entity):self {
        $key = spl_object_hash($entity);
        if(!array_key_exists($key, $this->entities))
            $this->entities[$key] = $entity;

        return $this;
    }

    /**
     * Remove une forme à la collection
     *
     * @param Form2d $form
     * 
     * @return self
     * 
     */
    public function remove(Entity $entity):self {
        $key = spl_object_hash($entity);
        if (array_key_exists($key, $this->entities))
            unset($this->entities[$key]);

        return $this;
    }

    /**
     * Contient une forme 
     *
     * @param Form2d $form
     * 
     * @return self
     * 
     */
    public function contains(Entity $entity): bool
    {
        $key = spl_object_hash($entity);
        if (array_key_exists($key, $this->entities))
            return true;

        return false;
    }

    #[\ReturnTypeWillChange] 
    public function current() :mixed {
        return current($this->entities);
    }

    public function key() :mixed {
        return key($this->entities);
    }

    public function next(): void {
        next($this->entities);
    }
    public function rewind(): void {
        reset($this->entities);
    }

    public function valid(): bool {
        return !is_null(key($this->entities));
    }

    public function count(): int {
        return count($this->entities);
    }

    /**
     * Get current collection of entities (same entitites)
     *
     * @return  array
     */
    public function getArray()
    {
        return array_values($this->entities);
    }

    /**
     * Permet de sérialisé le collection en JSON
     *
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->getArray();
    }
}