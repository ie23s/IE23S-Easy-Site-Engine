<?php

namespace ie23s\shop\system\auth\user\group;

class GroupModel
{
    private int $id;
    private string $name;
    private string $parents;

    /**
     * @param int $id
     * @param string $name
     * @param string $parents
     */
    public function __construct(int $id, string $name, string $parents)
    {
        $this->id = $id;
        $this->name = $name;
        $this->parents = $parents;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getParents(): string
    {
        return $this->parents;
    }


}