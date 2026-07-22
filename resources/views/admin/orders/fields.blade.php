<!-- Hidden timestamp fields -->
<input type="hidden" name="deleted_at" value="{{$order->deleted_at ?? ''}}">
<x-input-floating type="text" name="order_number" label="Order number" value="{{ $order->order_number ?? '' }}" />

<x-select name="dining_table_id" label="Dining table id" value="{{ $order->dining_table_id ?? '' }}" :options="$diningTables" />

<x-select name="order_type" label="Order type" value="{{ $order->order_type ?? '' }}" :options="['dine_in' => 'Dine_in', 'take_away' => 'Take_away', ]" />

<x-select name="status" label="Status" value="{{ $order->status ?? '' }}" :options="['pending' => 'Pending', 'confirmed' => 'Confirmed', 'preparing' => 'Preparing', 'ready' => 'Ready', 'completed' => 'Completed', 'cancelled' => 'Cancelled', ]" />

<x-select name="payment_status" label="Payment status" value="{{ $order->payment_status ?? '' }}" :options="['unpaid' => 'Unpaid', 'paid' => 'Paid', ]" />

<x-select name="payment_method" label="Payment method" value="{{ $order->payment_method ?? '' }}" :options="['cash' => 'Cash', 'qris' => 'Qris', 'transfer' => 'Transfer', ]" />

<x-input-floating type="text" name="subtotal" label="Subtotal" value="{{ $order->subtotal ?? '' }}" :isCurrency="true" />

<x-input-floating type="text" name="total" label="Total" value="{{ $order->total ?? '' }}" :isCurrency="true" />

<x-textarea-floating name="notes" label="Notes" value="{{ $order->notes ?? '' }}" />

<x-input-floating type="date" name="paid_at" label="Paid at" value="{{ isset($order->paid_at) && $order->paid_at ? (is_string($order->paid_at) ? $order->paid_at : $order->paid_at->format('Y-m-d')) : '' }}" />


@push('scripts')
    @include('admin.partials.form-styles')
    @php
        $hasTitleField = false;
        $hasNameField = false;
        $hasSlugField = false;
        $slugSourceField = null;
        $tagifyFields = array (
);
        $textareaFields = array (
  0 => 'notes',
);
        $selectFields = array (
  0 => 'dining_table_id',
  1 => 'order_type',
  2 => 'status',
  3 => 'payment_status',
  4 => 'payment_method',
);
        $currencyFields = array (
  0 => 'subtotal',
  1 => 'total',
);
        $passwordFields = array (
);
    @endphp
    @include('admin.partials.form-scripts')
@endpush