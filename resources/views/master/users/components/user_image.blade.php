

<div class="text-center" style="display: flex; align-items:center; justify-content: center;">
    <div class="preview-image"
        id="preview-image"
        style="width: 150px; position:relative; height: 150px; border-radius: 50%; background-image: @if(!$image) url(../../assets/img/blank.png); @else url(../../storage/{{ $folder  }}/{{ $image }}); @endif background-size: cover; background-repeat: no-repeat;">
        @if (!$image)
            <i class="fa fa-camera" id="icon-action-image" style="position: absolute; bottom: 10px; font-size: 18px; right: 18px; cursor: pointer;" onclick="selectImage()"></i>
        @else
            <i class="fa fa-times" id="icon-action-image" style="position: absolute; bottom: 10px; font-size: 18px; right: 18px; cursor: pointer; color: red;" onclick="removePreviewImage('edit')"></i>
        @endif
    </div>
</div>
<input type="file" name="file" onchange="showImage(event)" style="visibility: hidden;" id="user-image" accept=".png,.webp,.jpg">
<input type="hidden" name="is_delete_image" id="is_delete_image" value="0">