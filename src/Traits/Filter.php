<?php
namespace Muscobytes\CoresignalDbApi\Traits;

trait Filter
{
    /**
     * isAllowed
     * @param string $name
     * @return bool
     */
    public function isAllowed(string $name): bool
    {
        return in_array($name, $this->allowed);
    }


    /**
     * setFilter
     * @param array $filter
     * @return $this
     */
    public function setFilter(array $filter): self
    {
        if (!empty($filter)) {
            foreach ($filter as $name => $value) {
                if ($this->isAllowed($name)) {
                    $this->filters[$name] = $value;
                }
            }
        }
        return $this;
    }


    public function getFilters(): array
    {
        return $this->filters;
    }
}
