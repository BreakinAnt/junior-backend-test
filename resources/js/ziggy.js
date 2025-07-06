const Ziggy = {"url":"http:\/\/localhost","port":null,"defaults":{},"routes":{"contacts.index":{"uri":"contacts","methods":["GET","HEAD"]},"contacts.create":{"uri":"contacts\/create","methods":["GET","HEAD"]},"contacts.store":{"uri":"contacts","methods":["POST"]},"contacts.edit":{"uri":"contacts\/{id}\/edit","methods":["GET","HEAD"],"parameters":["id"]},"contacts.update":{"uri":"contacts\/{id}","methods":["PUT"],"parameters":["id"]},"contacts.destroy":{"uri":"contacts\/{id}","methods":["DELETE"],"parameters":["id"]},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
