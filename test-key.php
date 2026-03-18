<?php
// This file is for verifying whether PHP can see the OpenAI API key.
// Open in your browser at: http://localhost/alkelink/test-key.php

$key = getenv('OPENAI_API_KEY');
echo 'OPENAI_API_KEY=' . ($key !== false && $key !== '' ? $key : '<not set>');
