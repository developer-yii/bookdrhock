@php 
$type = isset($type) && !empty($type) ? $type : 'details';
$pagetype = isset($pagetype) && !empty($pagetype) ? $pagetype : '';
@endphp
@include('admin.poll.polldetail', ['type' => $type,'pagetype'=>$pagetype,'codeblock' => $codeblock])