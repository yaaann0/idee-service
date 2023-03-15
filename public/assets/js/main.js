paginationResize();
window.addEventListener('resize', paginationResize)

$('.delete-confirm').on('click', function (e) {
    if(window.confirm("Attention, êtes-vous sûr(e) de vousloir supprimer cet élément ? \n\nCette action est irréversible !")){
        return;
    } else {
        e.preventDefault();
    }
})

function paginationResize() {
    if (window.innerWidth < 492) {
        $('.pagination').addClass('pagination-sm')
    } else {
        $('.pagination').removeClass('pagination-sm')
    }
}

$('.table_line').on('click', function (e) {
    document.location.href = e.currentTarget.lastElementChild.firstElementChild.href
})


