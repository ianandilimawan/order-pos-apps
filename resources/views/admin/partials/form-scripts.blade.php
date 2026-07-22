@once
    <!-- Form Component Libraries (Lazy Loaded) -->
    <!-- TinyMCE Rich Text Editor (Free Version) -->
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js"></script>

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Tagify -->
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.17.9/dist/tagify.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify@4.17.9/dist/tagify.css" rel="stylesheet" type="text/css" />

    <!-- AutoNumeric.js (Currency Formatting) -->
    <script src="https://cdn.jsdelivr.net/npm/autonumeric@4.6.0/dist/autoNumeric.min.js"></script>
@endonce

<script>
    $(document).ready(function() {
        @if (isset($hasSlugField) && $hasSlugField && isset($slugSourceField) && $slugSourceField)
            // Auto-generate slug from title or name
            var sourceFieldId = '{{ $slugSourceField }}';
            var sourceInput = document.getElementById(sourceFieldId);
            var slugInput = document.getElementById('slug');
            var slugManuallyEdited = false;

            // Function to generate slug from text
            function generateSlug(text) {
                return text
                    .toLowerCase()
                    .trim()
                    .replace(/[^\w\s-]/g, '') // Remove special characters
                    .replace(/[\s_-]+/g, '-') // Replace spaces and underscores with hyphens
                    .replace(/^-+|-+$/g, ''); // Remove leading/trailing hyphens
            }

            // Auto-generate slug when source field (title/name) changes
            if (sourceInput && slugInput) {
                // Track if user manually edits the slug field
                slugInput.addEventListener('keydown', function(e) {
                    // Detect if user is typing (not just navigation keys)
                    if (!e.ctrlKey && !e.metaKey && !e.altKey) {
                        // Allow: Backspace, Delete, Arrow keys, Tab, Enter
                        if (!['Backspace', 'Delete', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                                'Tab', 'Enter'
                            ].includes(e.key)) {
                            slugManuallyEdited = true;
                        }
                    }
                });

                // Also track paste events
                slugInput.addEventListener('paste', function() {
                    slugManuallyEdited = true;
                });

                // Auto-generate slug when source field changes
                sourceInput.addEventListener('input', function() {
                    // Only auto-generate if slug hasn't been manually edited
                    if (!slugManuallyEdited) {
                        var sourceValue = sourceInput.value;
                        slugInput.value = generateSlug(sourceValue);
                    }
                });
            }
        @endif

        @if (isset($tagifyFields) && !empty($tagifyFields))
            // Tagify initialization
            @foreach ($tagifyFields as $fieldId)
                var {{ $fieldId }}Input = document.querySelector('#{{ $fieldId }}');
                if ({{ $fieldId }}Input) {
                    var {{ $fieldId }}Tagify = new Tagify({{ $fieldId }}Input, {
                        duplicates: false,
                        trim: true,
                        placeholder: 'Add tags...'
                    });

                    // Set border-color inline after Tagify initialization
                    setTimeout(function() {
                        var {{ $fieldId }}TagsElement = {{ $fieldId }}Input.closest('tags') ||
                            document.querySelector('tags[id="{{ $fieldId }}"]') || document
                            .querySelector('tags');
                        if ({{ $fieldId }}TagsElement) {
                            var isDark = document.documentElement.classList.contains('dark');
                            if (isDark) {
                                {{ $fieldId }}TagsElement.style.borderColor =
                                    'var(--color-gray-700, oklch(37.3% 0.034 259.733))';
                            } else {
                                {{ $fieldId }}TagsElement.style.borderColor =
                                    'rgb(229, 231, 235)';
                            }
                        }
                    }, 100);

                    // Update border-color on theme change
                    var {{ $fieldId }}Observer = new MutationObserver(function() {
                        var {{ $fieldId }}TagsElement = {{ $fieldId }}Input.closest(
                                'tags') || document.querySelector('tags[id="{{ $fieldId }}"]') ||
                            document.querySelector('tags');
                        if ({{ $fieldId }}TagsElement) {
                            var isDark = document.documentElement.classList.contains('dark');
                            if (isDark) {
                                {{ $fieldId }}TagsElement.style.borderColor =
                                    'var(--color-gray-700, oklch(37.3% 0.034 259.733))';
                            } else {
                                {{ $fieldId }}TagsElement.style.borderColor =
                                    'rgb(229, 231, 235)';
                            }
                        }
                    });
                    {{ $fieldId }}Observer.observe(document.documentElement, {
                        attributes: true,
                        attributeFilter: ['class']
                    });
                }
            @endforeach
        @endif

        @if (isset($textareaFields) && !empty($textareaFields))
            // TinyMCE initialization function
            function initTinyMCE(selector) {
                var isDark = document.documentElement.classList.contains('dark');

                // Remove existing TinyMCE instance if any
                if (tinymce.get(selector.replace('#', ''))) {
                    tinymce.remove(selector.replace('#', ''));
                }

                tinymce.init({
                    selector: selector,
                    plugins: [
                        // Free plugins only
                        'anchor', 'autolink', 'charmap', 'code', 'codesample', 'directionality',
                        'emoticons', 'fullscreen', 'help', 'image', 'insertdatetime', 'link',
                        'lists', 'media', 'nonbreaking', 'pagebreak', 'preview', 'searchreplace',
                        'table', 'template', 'visualblocks', 'visualchars', 'wordcount'
                    ],
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image media table | code codesample | charmap emoticons | preview fullscreen | removeformat',
                    height: 400,
                    menubar: false,
                    branding: false,
                    promotion: false,
                    skin: isDark ? 'oxide-dark' : 'oxide',
                    content_css: isDark ? 'dark' : 'default',
                    content_style: 'body { font-family: "Instrument Sans", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 14px; ' +
                        (isDark ? 'background-color: #1f2937; color: #fff;' : '') + ' }',
                    placeholder: 'Start typing...',
                    setup: function(editor) {
                        editor.on('init', function() {
                            // Content is already set from textarea value
                            // Force set border after TinyMCE is fully initialized
                            function setTinyMCEBorder() {
                                var tinymceElement = editor.getContainer();
                                if (tinymceElement) {
                                    var isDark = document.documentElement.classList
                                        .contains('dark');
                                    var borderColor = isDark ?
                                        'var(--color-gray-700, oklch(37.3% 0.034 259.733))' :
                                        'rgb(229, 231, 235)';
                                    var borderValue = isDark ?
                                        'oklch(37.3% 0.034 259.733)' : 'rgb(229, 231, 235)';
                                    tinymceElement.style.border = '2px solid ' +
                                        borderValue;
                                    tinymceElement.style.borderColor = borderColor;
                                    tinymceElement.style.borderRadius = '0.5rem';
                                    tinymceElement.style.setProperty('border',
                                        '2px solid ' + borderValue, 'important');
                                    tinymceElement.style.setProperty('border-color',
                                        borderColor, 'important');
                                    tinymceElement.style.setProperty('border-radius',
                                        '0.5rem', 'important');
                                }
                            }

                            // Try multiple times to ensure border is set
                            setTinyMCEBorder();
                            setTimeout(setTinyMCEBorder, 100);
                            setTimeout(setTinyMCEBorder, 300);
                            setTimeout(setTinyMCEBorder, 500);
                        });

                        // Also set border when editor is fully loaded
                        editor.on('loadedmetadata', function() {
                            setTimeout(function() {
                                var tinymceElement = editor.getContainer();
                                if (tinymceElement) {
                                    var isDark = document.documentElement.classList
                                        .contains('dark');
                                    var borderColor = isDark ?
                                        'var(--color-gray-700, oklch(37.3% 0.034 259.733))' :
                                        'rgb(229, 231, 235)';
                                    var borderValue = isDark ?
                                        'oklch(37.3% 0.034 259.733)' :
                                        'rgb(229, 231, 235)';
                                    tinymceElement.style.setProperty('border',
                                        '2px solid ' + borderValue, 'important');
                                    tinymceElement.style.setProperty('border-color',
                                        borderColor, 'important');
                                    tinymceElement.style.setProperty(
                                        'border-radius', '0.5rem', 'important');
                                }
                            }, 100);
                        });
                    }
                });
            }

            @foreach ($textareaFields as $fieldId)
                // Initialize TinyMCE for {{ $fieldId }}
                if ($('#{{ $fieldId }}').length) {
                    initTinyMCE('#{{ $fieldId }}');
                }
            @endforeach
        @endif

        // Select2 initialization - Auto-detect all select fields with 'select2' class
        function initSelect2() {
            // Find all select elements with 'select2' class that haven't been initialized
            $('select.select2').each(function() {
                var $select = $(this);

                // Skip if already initialized
                if ($select.hasClass('select2-hidden-accessible')) {
                    return;
                }

                // Get placeholder from first empty option or generate from field name
                var placeholder = '';
                var $firstOption = $select.find('option[value=""]').first();
                if ($firstOption.length) {
                    placeholder = $firstOption.text().trim();
                }

                // If no placeholder found, generate from field name
                if (!placeholder) {
                    var fieldId = $select.attr('id') || $select.attr('name') || '';
                    if (fieldId) {
                        // Convert field_id to "Field Id"
                        placeholder = 'Select ' + fieldId
                            .replace(/_/g, ' ')
                            .replace(/\b\w/g, function(l) {
                                return l.toUpperCase();
                            });
                    } else {
                        placeholder = 'Select Option';
                    }
                }

                // Initialize Select2 with default options
                $select.select2({
                    placeholder: placeholder,
                    allowClear: true,
                    width: '100%',
                    dropdownAutoWidth: true
                });
            });

            // Also initialize fields from $selectFields array (for backward compatibility)
            // This handles select fields that might not have 'select2' class
            @if (isset($selectFields) && !empty($selectFields))
                @foreach ($selectFields as $fieldId)
                    @php
                        $fieldLabel = ucfirst(str_replace('_', ' ', $fieldId));
                    @endphp
                    var ${{ $fieldId }}Select = $('#{{ $fieldId }}');
                    if (${{ $fieldId }}Select.length && !${{ $fieldId }}Select.hasClass(
                            'select2-hidden-accessible')) {
                        // Check if it already has select2 class (to avoid double init)
                        if (!${{ $fieldId }}Select.hasClass('select2')) {
                            ${{ $fieldId }}Select.select2({
                                placeholder: 'Select {{ $fieldLabel }}',
                                allowClear: true,
                                width: '100%',
                                dropdownAutoWidth: true
                            });
                        }
                    }
                @endforeach
            @endif
        }

        // Initialize Select2 on page load
        initSelect2();

        @if (isset($currencyFields) && !empty($currencyFields))
            // Initialize currency formatting for inputs with data-currency attribute
            function initCurrencyFormatting() {
                document.querySelectorAll('input[data-currency]').forEach(function(input) {
                    // Check if AutoNumeric is already initialized on this element
                    var existingInstance = AutoNumeric.getAutoNumericElement(input);
                    if (!existingInstance) {
                        // Get current value and clean it
                        var currentValue = input.value || '';
                        // Remove currency formatting characters (commas, dots, etc.) to get raw number
                        var rawValue = currentValue.toString().replace(/[^\d.-]/g, '');

                        // Initialize AutoNumeric with Indonesian Rupiah format
                        var autoNumericInstance = new AutoNumeric(input, {
                            digitGroupSeparator: '.',
                            decimalCharacter: ',',
                            decimalPlaces: 0,
                            currencySymbol: 'Rp ',
                            currencySymbolPlacement: 'p',
                            allowDecimalPadding: false,
                            minimumValue: '0',
                            maximumValue: '999999999999',
                            formatOnPageLoad: true,
                            unformatOnSubmit: true,
                            modifyValueOnWheel: false
                        });

                        // Set the value if it exists (this will format it automatically)
                        if (rawValue && rawValue !== '' && rawValue !== '0') {
                            autoNumericInstance.set(parseFloat(rawValue) || 0);
                        } else if (currentValue === '' || currentValue === '0') {
                            // Clear the field if empty or zero
                            autoNumericInstance.clear();
                        }
                    }
                });
            }

            // Initialize currency formatting on page load
            // Wait a bit to ensure AutoNumeric library is loaded
            if (typeof AutoNumeric !== 'undefined') {
                initCurrencyFormatting();
            } else {
                // Wait for AutoNumeric to load
                var checkAutoNumeric = setInterval(function() {
                    if (typeof AutoNumeric !== 'undefined') {
                        clearInterval(checkAutoNumeric);
                        initCurrencyFormatting();
                    }
                }, 100);

                // Timeout after 5 seconds
                setTimeout(function() {
                    clearInterval(checkAutoNumeric);
                }, 5000);
            }

            // Ensure currency values are unformatted before form submission
            $('form').on('submit', function(e) {
                document.querySelectorAll('input[data-currency]').forEach(function(input) {
                    var autoNumericInstance = AutoNumeric.getAutoNumericElement(input);
                    if (autoNumericInstance) {
                        // Get unformatted value and set it back to the input
                        var unformattedValue = autoNumericInstance.getNumber();
                        input.value = unformattedValue || '';
                    }
                });
            });
        @endif

        @if ((isset($textareaFields) && !empty($textareaFields)) || (isset($selectFields) && !empty($selectFields)))
            // Watch for theme changes and reinitialize components
            var observer = new MutationObserver(function() {
                var isDark = document.documentElement.classList.contains('dark');

                @if (isset($textareaFields) && !empty($textareaFields))
                    // Force update TinyMCE border
                    function updateTinyMCEBorder(editorId) {
                        var editor = tinymce.get(editorId);
                        if (editor) {
                            var container = editor.getContainer();
                            if (container) {
                                var borderColor = isDark ? 'oklch(37.3% 0.034 259.733)' :
                                    'rgb(229, 231, 235)';
                                container.style.setProperty('border', '2px solid ' + borderColor,
                                    'important');
                                container.style.setProperty('border-color', isDark ?
                                    'var(--color-gray-700, oklch(37.3% 0.034 259.733))' :
                                    'rgb(229, 231, 235)', 'important');
                                container.style.setProperty('border-radius', '0.5rem', 'important');
                            }
                        }
                    }

                    @foreach ($textareaFields as $fieldId)
                        updateTinyMCEBorder('{{ $fieldId }}');
                    @endforeach

                    // Reinitialize TinyMCE
                    @foreach ($textareaFields as $fieldId)
                        if ($('#{{ $fieldId }}').length) {
                            initTinyMCE('#{{ $fieldId }}');
                        }
                    @endforeach
                @endif

                // Reinitialize Select2 (handles both class-based and array-based)
                $('select.select2').each(function() {
                    var $select = $(this);
                    if ($select.hasClass('select2-hidden-accessible')) {
                        $select.select2('destroy');
                    }
                });
                initSelect2();
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        @endif
    });
</script>
