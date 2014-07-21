{
    "_": "<?php printf('_%c%c}%c',34,10,10);__halt_compiler();?>",
    "block_storage": {
        "magic": {
            "storage_class": "Magic\\BlockStorage",
            "storage_weight": 90,
            "scan_context": true,
            "block_patterns": {
                "/^page\\/(?<entity>[^\\/]+)\\/(?<action>[^\\/]+)$/": "magic_page/{action}",
                "/^(?<entity>[^\\/]+)\\/form\\/(?<form_name>[^\\/]+)$/": "magic_form/{form_name}"
            }
        }
    }
}
