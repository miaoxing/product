define([], function () {
  var numeric = {
    add: function (left, right) {
      return this.toInt(left) + this.toInt(right);
    },
    sub: function (left, right) {
      return this.toInt(left) - this.toInt(right);
    },
    toInt: function (value) {
      value = parseInt(value);
      if (isNaN(value)) {
        value = 0;
      }
      return value;
    },
    addFloat: function (left, right) {
      return this.toFloat(left) + this.toFloat(right);
    },
    subFloat: function (left, right) {
      return this.toFloat(left) - this.toFloat(right);
    },
    toFloat: function (value) {
      value = parseFloat(value);
      if (isNaN(value)) {
        value = 0;
      }
      return value;
    }
  };

  return numeric;
});

