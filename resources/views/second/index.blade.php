<x-app-layout>
    <h1>Second Index For Fill</h1>

    <div class="container">
        <h2>Create Planning Entry</h2>
        <form action="{{ route('second.store') }}" method="POST">
            @csrf

            <!-- Single entry fields -->
            <div class="form-group">
                <label for="plan_date">Plan Date</label>
                <input
                    type="date"
                    name="plan_date"
                    class="form-control"
                    required
                />
            </div>

            <div class="form-group">
                <label for="pic">PIC</label>
                <input type="text" name="pic" class="form-control" required />
            </div>

            <div class="form-group">
                <label for="shift">Shift</label>
                <input
                    type="number"
                    name="shift"
                    class="form-control"
                    required
                />
            </div>

            <hr />

            <!-- Repeatable section -->
            <div id="repeatable-section">
                <div class="repeatable-row">
                    <div class="form-group">
                        <label for="line[]">Line</label>
                        <input
                            type="text"
                            name="line[]"
                            class="form-control"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="item_code[]">Item Code</label>
                        <select
                            name="item_code[]"
                            class="form-control item-code-select"
                            required
                        >
                            <option value="" selected disabled>
                                Choose Item Code
                            </option>
                            @foreach ($master as $item)
                                <option
                                    value="{{ $item->item_code }}"
                                    data-description="{{ $item->item_description }}"
                                >
                                    {{ $item->item_code }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="item_description[]">Item Description</label>
                        <input
                            type="text"
                            name="item_description[]"
                            class="form-control item-description"
                            readonly
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label for="quantity_hour[]">Quantity Hour</label>
                        <input
                            type="number"
                            name="quantity_hour[]"
                            class="form-control"
                        />
                    </div>

                    <div class="form-group">
                        <label for="process_time[]">Process Time</label>
                        <input
                            type="number"
                            step="0.01"
                            name="process_time[]"
                            class="form-control"
                        />
                    </div>

                    <div class="form-group">
                        <label for="quantity_plan[]">Quantity Plan</label>
                        <input
                            type="number"
                            name="quantity_plan[]"
                            class="form-control"
                        />
                    </div>

                    <div class="form-group">
                        <label for="customer[]">Customer</label>
                        <input
                            type="text"
                            name="customer[]"
                            class="form-control"
                            required
                        />
                    </div>

                    <button type="button" class="btn btn-danger remove-row">
                        Remove
                    </button>
                    <hr />
                </div>
            </div>

            <button type="button" id="add-row" class="btn btn-primary">
                Add Another Item
            </button>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <!-- Include jQuery, Select2 CSS and JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link
        href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"
        rel="stylesheet"
    />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            initializeSelect2($('.item-code-select'));

            // Add row event handler
            $('#add-row').on('click', function () {
                let newRow = $('.repeatable-row:first').clone(); // Clone the first row

                // Clear values in the cloned row
                $(newRow).find('input').val('');
                $(newRow).find('select').val('').trigger('change');

                // Append the cloned row to the repeatable section
                $('#repeatable-section').append(newRow);

                // Initialize Select2 for new item-code-select elements in the cloned row only
                initializeSelect2($(newRow).find('.item-code-select'));
            });

            // Remove row event handler
            $('#repeatable-section').on('click', '.remove-row', function () {
                $(this).closest('.repeatable-row').remove();
            });
        });

        // Initialize Select2 for specific element(s) and handle autofill for item description
        function initializeSelect2(elements) {
            elements.select2();

            elements.off('change').on('change', function () {
                let description = $(this).find(':selected').data('description');
                $(this)
                    .closest('.repeatable-row')
                    .find('.item-description')
                    .val(description);
            });
        }
    </script>
</x-app-layout>
