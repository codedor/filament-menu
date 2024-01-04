document.addEventListener('alpine:initializing', () => {
  window.Alpine.data('menuSortableContainer', ({ statePath }) => ({
    statePath,
    sortable: null,
    init() {
      this.sortable = new Sortable(this.$el, {
        group: 'nested',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        draggable: '[data-sortable-item]',
        handle: '[data-sortable-handle]',
        onSort: () => {
          this.sorted()
        }
      })
    },
    sorted() {
      this.$wire.handleNewOrder(this.statePath, this.sortable.toArray())
    },
  }))
})
