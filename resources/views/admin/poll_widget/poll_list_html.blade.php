@php $type = isset($type) && !empty($type) ? $type : 'details' @endphp
@include('admin.poll_widget.poll_detail', ['type' => $type])
    
