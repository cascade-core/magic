{
    "_": "<?php printf('_%c%c}%c',34,10,10);__halt_compiler();?>",
    "block_storage": {
        "magic": {
            "storage_class": "Magic\\BlockStorage",
            "storage_weight": 90,
            "scan_context": true,
            "block_patterns": {
                "/^page\\/(?<entity>[^\\/]+)\\/(?<action>[^\\/]+)$/": {
                    "block_name_fmt": "magic_page/{action}",
                    "fallback_args": {
                        "action": "edit"
                    }
                },
                "/^(?<entity>[^\\/]+)\\/form\\/(?<form_name>[^\\/]+)$/": {
                    "block_name_fmt": "magic_form/{form_name}",
                    "fallback_args": {
                        "form_name": "confirm"
                    }
                }
            }
        }
    }
}
