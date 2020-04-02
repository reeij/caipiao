<?php

namespace app\interfaces;

interface Generator {

    const MIN_SEED_NUMBER = 1;

    const MAX_SEED_NUMBER = 33;

    public function piece();

    public function groups();

}