<?php

arch()->preset()->php();
arch()->preset()->security();

arch('no debug statements left in src/')
    ->expect(['dd', 'dump', 'ray', 'var_dump'])
    ->not->toBeUsed();
