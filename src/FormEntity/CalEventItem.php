<?php

namespace Celtic34fr\ContactRendezVous\FormEntity;

class CalEventItem
{
    private ?int $id = null;
    private ?string $cle;
    private ?string $fonction;
    private ?string $background;
    private ?string $border;
    private ?string $text;

    /**
     * @param string $jsonStr
     * @return CalEventItem|bool
     */ 
    public function hydrateFromJson(string $jsonStr):mixed
    {
        $jsonArray = json_decode($jsonStr, true);
        if (!empty($jsonStr) && is_string($jsonStr) 
            && is_array($jsonArray) && !empty($jsonArray) 
            && json_last_error() == 0) {
            foreach ($jsonArray as $key => $val) {
                $method = "set" . ucfirst($key);
                $this->$method($val);
            }
            return $this;
        }
        return false;
    }

    /**
     * @return bool|string
     */ 
    public function getValaur(): bool|string
    {
        $jsonArray = [
            'cle' => $this->getCle(),
            'fonction' => $this->getFonction(),
            'background' => $this->getBackground(),
            'border' => $this->getBorder(),
            'text' => $this->getText()
        ];
        return json_encode($jsonArray);
    }

    /**
     * @return null|int
     */ 
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $is
     * @return CalEventItem
     */ 
    public function setId(int $id) : self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return null|string
     */ 
    public function getCle(): ?string
    {
        return $this->cle;
    }

    /**
     * @param string $cle
     * @return CalEventItem
     */ 
    public function setCle(string $cle): self
    {
        $this->cle = $cle;
        return $this;
    }

    /**
     * @return null|string
     */ 
    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    /**
     * @param string $fonction
     * @return CalEventItem
     */ 
    public function setFonction(string $fonction): self
    {
        $this->fonction = $fonction;
        return $this;
    }

    /**
     * @return null|string
     */ 
    public function getBackground(): ?string
    {
        return $this->background;
    }

    /**
     * @param string $background
     * @return CalEventItem|bool
     */ 
    public function setBackground(string $background): mixed
    {
        if ($this->validColorHexa($border)) {
            $this->background = $background;
            return $this;
        }
        return false;
    }

    /**
     * @return null|string
     */ 
    public function getBorder(): ?string
    {
        return $this->border;
    }

    /**
     * @param string $border
     * @return CalEventItem|bool
     */ 
    public function setBorder(string $border): mixed
    {
        if ($this->validColorHexa($border)) {
            $this->border = $border;
            return $this;
        }
        return false;
    }

    /**
     * @return null|string
     */ 
    public function getText(): ?string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return CalEventItem|bool
     */ 
    public function setText($text): mixed
    {
        if ($this->validColorHexa($text)) {
            $this->text = $text;
            return $this;
        }
        return false;
    }

    private function validColorHexa(string $color_str): bool
    {
        /** validation de la chaîne de caractères
         *      -> commencer par '#'
         *      -> par groupe de 2 caractères : valeur hexadéciaml de 0 à 255 : 00 à FF
         */
        if (substr($color_str, 0, 1) != "#") return false;
        if (!ctype_xdigit(substr($color_str, 1))) return false;
        return true;
    }
}
