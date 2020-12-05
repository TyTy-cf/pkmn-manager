<?php


namespace App\Entity\Traits;


use Doctrine\ORM\Mapping as ORM;

trait TraitApi
{
    /**
     * @var string $idApi
     *
     * @ORM\Column(name="id_api", type="string", length=10, nullable=true)
     */
    private string $idApi;

    /**
     * @var string $nameApi
     *
     * @ORM\Column(name="name_api", type="string", length=255, nullable=true)
     */
    private string $nameApi;

    /**
     * @return string
     */
    public function getIdApi(): string
    {
        return $this->idApi;
    }

    /**
     * @param string $idApi
     */
    public function setIdApi(string $idApi): void
    {
        $this->idApi = $idApi;
    }

    /**
     * @return string
     */
    public function getNameApi(): string
    {
        return $this->nameApi;
    }

    /**
     * @param string $nameApi
     */
    public function setNameApi(string $nameApi): void
    {
        $this->nameApi = $nameApi;
    }

}