<style>
    .skeleton-table {
        background-image: linear-gradient( 90deg, rgb(184, 184, 184), rgb(198, 198, 198), #CACACA, #DFDCDC);
        background-size: 1000%;
        background-position: right;
        border-radius: 4px;
        width: 100%;
        height: 30px;
        animation: sweep 1s ease-in-out infinite alternate;
        box-shadow: 0 0 6px 0 #a3a3a331;
    }
    .skeleton-table.header {
        height: 30px;
    }
    .skeleton-table.body {
        height: 150px;
        margin-top: 10px;
    }
    .skeleton-component {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .skeleton-table.filter-left {
        width: 200px;
        height: 30px;
    }
    .skeleton-table.filter-right {
        width: 150px;
        height: 30px;
    }
    @keyframes sweep {
        0% {
            background-position: right;
        }
        100% {
            background-position: left;
        }
    }
</style>

<div class="skeleton-component">
    <div class="skeleton-table filter-left"></div>
    <div class="skeleton-table filter-right"></div>
</div>
<div class="skeleton-table header"></div>
<div class="skeleton-table body"></div>

