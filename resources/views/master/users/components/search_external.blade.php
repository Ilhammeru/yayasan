<style type="text/css">
	.search-group {
		border: 1px solid #e6e6e6;
		display: flex;
		align-items: center;
		justify-content: space-between;
		padding: 10px;
		margin-bottom: 10px;
		border-radius: 8px;
	}
	.search-group .left {
		display: flex;
		align-items: center;
		gap: 20px;
	}
	.search-group .left .title {
		font-weight: bold;
		font-size: 16px;
		margin: 0;
	}
	.user-type-wrapper {
		background: #f7f7f6;
		border-radius: 8px;
		padding: 5px;
		display: flex;
		align-items: center;
		gap: 6px;
	}
	.user-type-wrapper .user-type-option {
		background: transparent;
		padding: 2px 8px;
		border-radius: 8px;
		cursor: pointer;
	}
	.user-type-wrapper .user-type-option.active {
		background: #fff;
		border: .5px solid #acb3ac;
	}
	.search-group .right {
		display: flex;
		align-items: center;
		gap: 20px;
	}
	.search-group .right .input-group {
		width: 130%;
	}
</style>

<div class="search-group">
	<div class="left">
		<p class="title">{{ __('view.user_external') }}</p>
		<div class="user-type-wrapper">
			<div class="user-type-option" id="search-type-0" onclick="searchUserType('0')" date-type="0">{{ __('view.all') }}</div>
			<div class="user-type-option active" id="search-type-2" onclick="searchUserType('2')" data-type="2">{{ __('view.goverment') }}</div>
			<div class="user-type-option" id="search-type-1" onclick="searchUserType('1')" date-type="1">{{ __('view.public') }}</div>
		</div>
	</div>
	<div class="right">
		<select class="form-control" id="search-user-status"
			data-placeholder="{{ __('view.search_user_status') }}" onchange="searchStatus(this)">
			<option></option>
			<option value="all">{{ __('view.all') }}</option>
			<option value="1">{{ __('view.active') }}</option>
			<option value="0">{{ __('view.inactive') }}</option>
		</select>
		<div class="input-group">
            <input type="text" id="search-all" name="search-all" class="form-control" placeholder="{{ __('view.search_anything') }}" oninput="searchAll(this)">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
        </div>
	</div>
</div>