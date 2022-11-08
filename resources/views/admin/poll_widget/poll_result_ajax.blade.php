@php 
$type = isset($type) && !empty($type) ? $type : 'details';
$pagetype = isset($pagetype) && !empty($pagetype) ? $pagetype : '';
@endphp
@include('admin.poll_widget.poll_detail', ['type' => $type,'pagetype'=>$pagetype])