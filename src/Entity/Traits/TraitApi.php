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
     * @return TraitApi
     */
    public function setIdApi(string $idApi): self
    {
        $this->idApi = $idApi;
        return $this;
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
     * @return TraitApi
     */
    public function setNameApi(string $nameApi): self
    {
        $this->nameApi = $nameApi;
        return $this;
    }

}