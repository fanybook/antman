<?php

namespace Antman\Master;

class Master
{
    public function start()
    {
        $this
            ->setName('dig {job}')
            ->setDescription('Let ant execute a dig job.')
            ->setHelp('find/create a job in \'src/Jobs\'');
    }
}
