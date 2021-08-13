<?php

namespace DanialRahimy\Slug;

class Slug
{
    protected array $slugAble = [];
    protected string $divider = '-';

    public function __construct()
    {
        $this->slugAble = [
            ' ', "\n", "\r", ",", "،", '¬', 'å'
        ];
    }

    /**
     * @param array $slugAble
     */
    public function setSlugAble(array $slugAble)
    {
        $this->slugAble = $slugAble;
    }

    /**
     * @param string $convertTo
     */
    public function setDivider(string $convertTo)
    {
        $this->divider = $convertTo;
    }

    /**
     * @param array $slugAble
     */
    public function addToSlugAble(array $slugAble)
    {
        $this->slugAble = array_merge($this->slugAble, $slugAble);
        $this->slugAble = array_unique($this->slugAble);
    }

    /**
     * @param array $slugAble
     */
    public function removeFromSlugAble(array $slugAble)
    {
        foreach ($slugAble as $item) {
            if (!in_array($item, $this->slugAble))
                continue;

            $key = array_search($item, $this->slugAble);

            unset($this->slugAble[$key]);

            $this->slugAble = array_values($this->slugAble);
        }
    }

    /**
     * @param string $input
     * @return string
     */
    public function make(string $input): string
    {
        $output = trim($input);

        $output = str_replace($this->slugAble, $this->divider, $output);

        $output = preg_replace('~' . $this->divider . '+~', $this->divider, $output);

        if (!is_string($output))
            $output = '';

        $output = strtolower($output);

        return trim($output, $this->divider);
    }
}
