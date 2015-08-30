<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model as Model;

/**
 * Profile
 *
 * @ORM\Entity(repositoryClass="ProfileRepository")
 * @ORM\Table(name="profile")
 */
class Profile
{
    use Model\IdClassTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text", nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="surname", type="text", nullable=false)
     */
    protected $surname;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=false)
     */
    protected $description;

    /**
     * @ORM\OneToOne(targetEntity="ProfileExtended", mappedBy="profile", cascade={"persist", "remove"})
     **/
    private $profileExtended;

    public function __toString()
    {
        return sprintf('%s %s', $this->name, $this->surname);
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Profile
     */
    public function setName($name)
    {
        $this->name = $name;
    
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set surname
     *
     * @param string $surname
     * @return Profile
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    
        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Profile
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set profileExtended
     *
     * @param ProfileExtended $profile
     * @return Profile
     */
    public function setProfileExtended(ProfileExtended $profileExtended)
    {
        $this->profileExtended = $profileExtended;

        $profileExtended->setProfile($this);
    
        return $this;
    }

    /**
     * Get profileExtended
     *
     * @return ProfileExtended
     */
    public function getProfileExtended()
    {
        return $this->profileExtended;
    }
}
