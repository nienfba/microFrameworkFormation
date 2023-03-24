<?php

namespace Fab\Classes;

use Fab\Classes\Entity;
use Fab\Classes\Database;
use Fab\Classes\EntityCollection;


class Model
{

    /**
     * @var Database 
     */
    private Database $db;

    /**
     * @var string $table la table dans la base de données
     */
    protected string $table;

    
    public function __construct()
    {
        $this->db = new Database();
    }


    /**
     * Retourne une entité à partir de son id
     * @param int $id
     * 
     * @return Entity|null
     */
    public function find(int $id): ?Entity
    {
        $result = $this->db->selectOne('SELECT * FROM ' . $this->table . ' WHERE id=:id', ['id' => $id]);

        $product = $this->hydrate($result);

        return $product;
    }


    /**
     * Retourne une collection d'entité
     * @param void
     * 
     * @return EntityCollection|null
     */
    public function findAll(): ?EntityCollection
    {
        $result = $this->db->select('SELECT * FROM ' . $this->table);

        $product = $this->hydrateCollection($result);

        return $product;
    }


    /**
     * Purge les données de la table !
     * ATTENTION : dangereux de permettre ceci ! 
     * On s'en sert pour hydrater la table avec des données fake !
     */
    public function truncate() {
        $this->db->query('TRUNCATE ' . $this->table);
    }

    /** Save entity in DATA 
     * @param Entity $entity the entity object to save on DATA
     * @return void
     * 
     * @todo implement saveChildren/ update children ENTITY COLLECTION
     */
    public function save(Entity $entity): ?int
    {
        // Récupération des colonne et propriétés de notre Entité
        $properties = $entity->getPropertyHydrateData($this);

        // On parcours toutes les propriétés pour les adaptés à la Data

        $listCols = '';
        $listTokens = '';
        $listColsTokens = '';
        $i = 0;
        foreach ($properties as $colonne => $value) {

            // DATETIME convert to string to write in Data !!
            if (substr($colonne, -2)==='At') {
                unset($properties[$colonne]);
                $colonne = str_replace('At', '_at', $colonne);
                if ($value != null)
                    $properties[$colonne] = $value['date'];
                else
                    $properties[$colonne] = $value;
            }

            //ENTITY : getting ID to write in Data !!
            if (gettype($value) == 'object' && is_subclass_of($value, 'Nienfba\Framework\Entity')) {

                unset($properties[$colonne]);
                $colonne = $colonne . '_id';
                $properties[$colonne] = $value->getId();
            }

            /*  // ENTITY COLLECTION : don't do anything at this time !
            if (gettype($value) == 'object' && get_class($value) == 'Nienfba\Framework\EntityCollection') {
            } */


            // CREATING STRING FOR DATA REQUEST 
            $listCols .= $colonne;
            $listTokens .= ':' . $colonne;
            $listColsTokens .= "{$colonne} = :{$colonne}";
            if (++$i < count($properties)) {
                $listCols .= ',';
                $listTokens .= ',';
                $listColsTokens .= ',';
            }
        }

        if ($entity->getId() == null) {
            $sql = "INSERT INTO {$this->table} ({$listCols}) VALUES ({$listTokens})";
            $entity->setId($this->db->query($sql, $properties));
        } else {
            $sql = "UPDATE {$this->table} SET $listColsTokens WHERE id = :id";
            $this->db->query($sql, $properties);
        }

        return $entity->getId();
    }


    /**
     * Créée une entité à partir du nom de la table
     */
    public function getEntity()
    {
        $entityClassName = 'Fab\Entity\\' . ucfirst($this->table);
        return new $entityClassName();
    }

    /**
     * Hydrate la collection d'entité avec les données récupérées
     * Devrait etre dans une classe EntityHydrate / EntityPopulate ?
     * 
     * @param array $data : un tableau associatif contenant les données à hydrater
     */
    public function hydrateCollection($data): ?EntityCollection
    {

        $entityCollection = new EntityCollection();

        if (!empty($data)) {
            foreach ($data as $value) {
                $entityCollection->add($this->hydrate($value));
            }
        }
        return $entityCollection;
    }

    /**
     * Hydrate l'entité avec les données récupérées
     * Devrait etre dans une classe EntityHydrate / EntityPopulate ?
     * 
     * @param array $data : un tableau associatif contenant les données à hydrater
     */
    public function hydrate(?array $data): Entity
    {
        $entity = $this->getEntity();

        if (!empty($data)) {
            foreach ($data as $property => $value) {
                $propertySpec = explode('_', $property);
                if (count($propertySpec) > 1) {
                    $method = 'set' . $propertySpec[0] . ucfirst($propertySpec[1]);
                    // c'est une date
                    if ($propertySpec[1] == 'at')
                        $value = new \DateTime($value);

                    // si c'est une autre entité (exemple) : dans la base par exemple category_id
                    // on va charger l'entité directement à partir du modèle !
                    if ($propertySpec[1] == 'id') {
                        $modelName = ucfirst($propertySpec[0]) . 'Model';
                        $entityModel = new $modelName();
                        $value = new $entityModel->find($value);
                    }
                } else
                    $method = 'set' . ucfirst($property);

                if (method_exists($entity, $method))
                    $entity->$method($value);
            }
        }

        return $entity;
    }
}
