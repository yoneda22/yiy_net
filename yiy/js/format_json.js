(function(window, $) {

  /**
    JSON整形 Model クラス
   */
  var FormatJsonModel = Backbone.Model.extend({

    default: {
      checkDateTime : null,
      inputText     : '',
      outputText    : ''
    }
  });


  /**
   JSON整形 View クラス
  */
  var FormatJsonView = BackBone.View.extend({

    el       : '#history',
    template : '#formatHistoryTemplate',
    model    : new FormatJsonModel,
  });

})(window, jQuery)
