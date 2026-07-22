<style>
    /* Placeholder color for dark mode */
    .dark input::placeholder,
    .dark textarea::placeholder {
        color: #9ca3af !important;
    }

    /* Tagify Styles - Match meta title exactly */
    tagify {
        border: 0 !important;
        border-radius: 0.5rem !important;
        padding: 0 !important;
        min-height: 60px !important;
        height: 60px !important;
        width: 100% !important;
        max-width: 100% !important;
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        /* shadow-md - same as meta title */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1) !important;
        box-sizing: border-box !important;
        flex: 1 1 100% !important;
    }

    /* Ensure tags are vertically centered - match meta title height exactly */
    tagify .tagify__tags {
        display: flex !important;
        align-items: center !important;
        flex-wrap: wrap !important;
        padding: 16px !important;
        min-height: 60px !important;
        height: 100% !important;
        width: 100% !important;
        gap: 6px !important;
        box-sizing: border-box !important;
    }

    tagify .tagify__tag {
        display: inline-flex !important;
        align-items: center !important;
        margin: 0 !important;
        height: auto !important;
        line-height: 1.5 !important;
        vertical-align: middle !important;
    }

    /* Ensure tagify input wrapper is centered */
    tagify .tagify__input {
        display: inline-flex !important;
        align-items: center !important;
        vertical-align: middle !important;
        min-height: 28px !important;
        line-height: 1.5 !important;
    }

    .dark tagify {
        border: 2px solid oklch(37.3% 0.034 259.733) !important;
        border-color: var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
        background-color: #111827 !important;
    }

    /* Light mode - no border like meta title */
    tagify:not(.dark) {
        border: 0 !important;
    }

    tagify:focus-within {
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5) !important;
    }

    .dark tagify:focus-within {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }

    /* Tagify Input Styling - Override py-4 padding */
    tagify .tagify__input {
        padding: 0 !important;
        margin: 0 !important;
        min-height: auto !important;
        height: auto !important;
        line-height: 1.5 !important;
        color: #111827 !important;
        display: inline-flex !important;
        align-items: center !important;
        flex: 1 !important;
    }

    /* Override input padding when inside tagify */
    tagify input.tagify__input {
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    /* Style input before tagify initializes - match tagify styling */
    input#meta_keywords:not([data-tagify]) {
        height: 60px !important;
        min-height: 60px !important;
        display: flex !important;
        align-items: center !important;
    }

    .dark .tagify__input {
        color: #fff !important;
    }

    /* Tagify Placeholder - Improve readability */
    tagify[data-placeholder]:empty::before {
        color: #6b7280 !important;
        opacity: 1 !important;
        font-size: 14px !important;
        padding: 0 !important;
        line-height: 1.5 !important;
        display: flex !important;
        align-items: center !important;
    }

    .dark tagify[data-placeholder]:empty::before {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    /* Tagify Empty Placeholder - Match text color */
    .tagify--empty .tagify__input::before {
        color: #111827 !important;
        opacity: 1 !important;
    }

    .dark .tagify--empty .tagify__input::before {
        color: #fff !important;
        opacity: 1 !important;
    }

    .tagify__input::placeholder {
        color: #6b7280 !important;
        opacity: 1 !important;
    }

    .dark .tagify__input::placeholder {
        color: #9ca3af !important;
        opacity: 1 !important;
    }

    /* TomSelect Border and Styling - Match other fields */
    .ts-wrapper .ts-control {
        border: 2px solid rgb(229, 231, 235) !important;
        border-color: rgb(229, 231, 235) !important;
        border-radius: 0.5rem !important;
        height: auto !important;
        min-height: 56px !important;
        padding: 14px 16px !important;
        --tw-shadow: 0 4px 6px -1px var(--tw-shadow-color, rgb(0 0 0 / 0.1)), 0 2px 4px -2px var(--tw-shadow-color, rgb(0 0 0 / 0.1)) !important;
        box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow) !important;
        background-color: transparent !important;
    }

    .dark .ts-wrapper .ts-control {
        border: 2px solid var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
        border-color: var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
    }
    
    .dark .ts-wrapper .ts-control .item, .dark .ts-wrapper .ts-control input {
        color: #fff !important;
    }

    .ts-wrapper.focus .ts-control,
    .ts-wrapper.input-active .ts-control {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
        outline: none !important;
    }

    /* TomSelect Dropdown */
    .ts-dropdown {
        border: 2px solid rgb(229, 231, 235) !important;
        border-color: rgb(229, 231, 235) !important;
        border-radius: 0.5rem !important;
        --tw-shadow: 0 10px 15px -3px var(--tw-shadow-color, rgb(0 0 0 / 0.1)), 0 4px 6px -2px var(--tw-shadow-color, rgb(0 0 0 / 0.05)) !important;
        box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow) !important;
        margin-top: 4px;
    }

    .dark .ts-dropdown {
        background-color: #1f2937 !important;
        border: 2px solid var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
        border-color: var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
        color: #fff !important;
    }

    .ts-dropdown .option {
        padding: 12px 16px !important;
    }

    .dark .ts-dropdown .option {
        background-color: #1f2937 !important;
        color: #fff !important;
    }

    .dark .ts-dropdown .active {
        background-color: #374151 !important;
        color: #fff !important;
    }

    /* TinyMCE Border and Placeholder Styles - Match Tagify exactly */
    .tox-tinymce {
        border: 2px solid rgb(229, 231, 235) !important;
        border-color: rgb(229, 231, 235) !important;
        border-radius: 0.5rem !important;
        --tw-shadow: 0 4px 6px -1px var(--tw-shadow-color, rgb(0 0 0 / 0.1)), 0 2px 4px -2px var(--tw-shadow-color, rgb(0 0 0 / 0.1)) !important;
        box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow) !important;
    }

    .dark .tox-tinymce {
        border: 2px solid var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
        border-color: var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
    }

    .tox-tinymce.tox-tinymce--focus {
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
    }

    .tox .tox-edit-area__iframe {
        background-color: #fff !important;
    }

    .dark .tox .tox-edit-area__iframe {
        background-color: #1f2937 !important;
    }

    /* Placeholder color for TinyMCE */
    .tox .tox-edit-area p[data-mce-placeholder] {
        color: #9ca3af !important;
    }

    .dark .tox .tox-edit-area p[data-mce-placeholder] {
        color: #9ca3af !important;
    }

    /* Remove border for regular inputs in light mode (keep for TinyMCE, Tagify, Select2) */
    input[type="text"]:not([id*="keywords"]),
    input[type="email"],
    input[type="number"],
    input[type="date"],
    input[type="password"],
    textarea:not([id*="description"]):not([id*="meta_description"]) {
        border: none !important;
        border-width: 0 !important;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06) !important;
    }

    /* Force shadow for regular inputs in light mode - match Tagify */
    input[type="text"]:not([id*="keywords"]),
    input[type="email"],
    input[type="number"],
    input[type="date"],
    input[type="password"] {
        --tw-shadow: 0 4px 6px -1px var(--tw-shadow-color, rgb(0 0 0 / 0.1)), 0 2px 4px -2px var(--tw-shadow-color, rgb(0 0 0 / 0.1)) !important;
        box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow) !important;
    }

    /* Ensure shadow even when focused (but add focus ring) */
    input[type="text"]:not([id*="keywords"]):focus,
    input[type="email"]:focus,
    input[type="number"]:focus,
    input[type="date"]:focus,
    input[type="password"]:focus {
        --tw-shadow: 0 4px 6px -1px var(--tw-shadow-color, rgb(0 0 0 / 0.1)), 0 2px 4px -2px var(--tw-shadow-color, rgb(0 0 0 / 0.1)) !important;
        --tw-ring-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2) !important;
        box-shadow: var(--tw-inset-shadow), var(--tw-inset-ring-shadow), var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow) !important;
    }

    .dark input[type="text"]:not([id*="keywords"]),
    .dark input[type="email"],
    .dark input[type="number"],
    .dark input[type="date"],
    .dark input[type="password"],
    .dark textarea:not([id*="description"]):not([id*="meta_description"]) {
        border: 2px solid var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
        border-color: var(--color-gray-700, oklch(37.3% 0.034 259.733)) !important;
    }
</style>
