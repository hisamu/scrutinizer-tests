#!/bin/bash

echo '<?xml version="1.0" encoding="UTF-8"?>
<checkstyle version="1.0.0">
    <file name="./src/Bar.php">
        <error line="2"  message="Test" source="Ruleset.RuleName"/>
    </file>
</checkstyle>'
