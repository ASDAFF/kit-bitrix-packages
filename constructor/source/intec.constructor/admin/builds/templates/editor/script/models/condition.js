(function (models) {
    (function (condition) {
        condition.on('init', function (event, self, data, manager) {
            if (self.type() === 'group') {
                self.conditions.subscribe(function () {
                    if (manager.conditions.isLocked())
                        return;

                    manager.conditions.lock();
                    self.conditions.sort(function (a, b) {
                        if (a.type() === 'group' && b.type() !== 'group') {
                            return -1;
                        } else if (a.type() !== 'group' && b.type() === 'group') {
                            return 1;
                        }

                        return 0;
                    });
                    manager.conditions.unlock();
                });
            }
        });
    })(models.condition);
});