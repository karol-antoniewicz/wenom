interface EventBus {
    events: Record<string, Function[]>
    $on(eventName: string, callback: Function): void
    $off(eventName: string, callback: Function): void
    $emit(eventName: string, payload: any): void
}
  
const eventBus: EventBus = {
    events: {},

    $on(eventName: string, callback: Function): void {
      this.events[eventName] = this.events[eventName] || []
      this.events[eventName].push(callback)
    },

    $off(eventName: string, callback: Function): void {
      if (this.events[eventName]) {
        this.events[eventName] = this.events[eventName].filter(
            (cb: Function): boolean => cb !== callback
        )
      }
    },

    $emit(eventName: string, payload: any): void {
      if (this.events[eventName]) {
        this.events[eventName].forEach(
            (cb: Function): void => cb(payload)
        )
      }
    }
  };
  
  export default eventBus;