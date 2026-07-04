module.exports = function (grunt) {
  "use strict";

  var versions = {
      git: "git",
      3.1: "3.1.0",
      "3.0": "3.0.0",
      2.2: "2.2.4",
      2.1: "2.1.4",
      "2.0": "2.0.3",
      1.12: "1.12.4",
      1.11: "1.11.3",
      "1.10": "1.10.2",
      1.9: "1.9.1",
      1.8: "1.8.3",
      1.7: "1.7.2",
    },
    tests = {
      Accordion: "accordion/accordion.php",
      Autocomplete: "autocomplete/autocomplete.php",
      Button: "button/button.php",
      Checkboxradio: "checkboxradio/checkboxradio.php",
      Controlgroup: "controlgroup/controlgroup.php",
      Core: "core/core.php",
      Datepicker: "datepicker/datepicker.php",
      Dialog: "dialog/dialog.php",
      Draggable: "draggable/draggable.php",
      Droppable: "droppable/droppable.php",
      Effects: "effects/effects.php",
      "Form Reset Mixin": "form-reset-mixin/form-reset-mixin.php",
      Menu: "menu/menu.php",
      Position: "position/position.php",
      Progressbar: "progressbar/progressbar.php",
      Resizable: "resizable/resizable.php",
      Selectable: "selectable/selectable.php",
      Selectmenu: "selectmenu/selectmenu.php",
      Slider: "slider/slider.php",
      Sortable: "sortable/sortable.php",
      Spinner: "spinner/spinner.php",
      Tabs: "tabs/tabs.php",
      Tooltip: "tooltip/tooltip.php",
      Widget: "widget/widget.php",
    };

  function submit(commit, runs, configFile, extra, done) {
    var testName,
      testswarm = require("testswarm"),
      config = grunt.file.readJSON(configFile).jqueryui,
      browserSets = config.browserSets,
      commitUrl = "https://github.com/jquery/jquery-ui/commit/" + commit;

    if (extra) {
      // jQuery >= 2.0.0 don't support IE 8.
      if (extra.substring(0, 6) !== "core 1") {
        browserSets = "jquery-ui-future";
      }

      extra = " (" + extra + ")";
    }

    for (testName in runs) {
      runs[testName] =
        config.testUrl + commit + "/tests/unit/" + runs[testName];
    }

    testswarm
      .createClient({
        url: config.swarmUrl,
      })
      .addReporter(testswarm.reporters.cli)
      .auth({
        id: config.authUsername,
        token: config.authToken,
      })
      .addjob(
        {
          name:
            "Commit <a href='" +
            commitUrl +
            "'>" +
            commit.substr(0, 10) +
            "</a>" +
            extra,
          runs: runs,
          runMax: config.runMax,
          browserSets: browserSets,
          timeout: 1000 * 60 * 30,
        },
        function (error, passed) {
          if (error) {
            grunt.log.error(error);
          }
          done(passed);
        }
      );
  }

  grunt.registerTask("testswarm", function (commit, configFile) {
    var test,
      latestTests = {};
    for (test in tests) {
      latestTests[test] = tests[test] + "?nojshint=true";
    }
    submit(commit, latestTests, configFile, "", this.async());
  });

  grunt.registerTask(
    "testswarm-multi-jquery",
    function (commit, configFile, minor) {
      var allTests = {};
      versions[minor].split(" ").forEach(function (version) {
        for (var test in tests) {
          allTests[test + "-" + version] =
            tests[test] + "?nojshint=true&jquery=" + version;
        }
      });
      submit(commit, allTests, configFile, "core " + minor, this.async());
    }
  );
};
