<?php

// Require generic view 
require_once $_SERVER['DOCUMENT_ROOT'] . 'views/View.php';

genViewHeader( null );
genPageHeader( null, null );
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html class="pageview">


<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<base target="_top">
<style type="text/css">
  

/* default css */

table {
  font-size: 1em;
  line-height: inherit;
  border-collapse: collapse;
}


tr {
  
  text-align: left;
  
}


div, address, ol, ul, li, option, select {
  margin-top: 0px;
  margin-bottom: 0px;
}

p {
  margin: 0px;
}


pre {
  font-family: Courier New;
  white-space: pre-wrap;
  margin:0;
}

body {
  margin: 6px;
  padding: 0px;
  font-family: Verdana, sans-serif;
  font-size: 10pt;
  background-color: #ffffff;
}


img {
  -moz-force-broken-image-icon: 1;
}

@media screen {
  html.pageview {
    background-color: #f3f3f3 !important;
    overflow-x: hidden;
    overflow-y: scroll;
  }

  

  body {
    min-height: 1100px;
    
    counter-reset: __goog_page__;
  }
  * html body {
    height: 1100px;
  }
  .pageview body {
    border-top: 1px solid #ccc;
    border-left: 1px solid #ccc;
    border-right: 2px solid #bbb;
    border-bottom: 2px solid #bbb;
    width: 648px !important;
    margin: 15px auto 25px;
    padding: 40px 50px;
  }
  /* IE6 */
  * html {
    overflow-y: scroll;
  }
  * html.pageview body {
    overflow-x: auto;
  }
  /* Prevent repaint errors when scrolling in Safari. This "Star-7" css hack
     targets Safari 3.1, but not WebKit nightlies and presumably Safari 4.
     That's OK because this bug is fixed in WebKit nightlies/Safari 4 :-). */
  html*#wys_frame::before {
    content: '\A0';
    position: fixed;
    overflow: hidden;
    width: 0;
    height: 0;
    top: 0;
    left: 0;
  }
  
  

  
    
    .writely-callout-data {
      display: none;
    }
    

    .writely-footnote-marker {
      background-image: url('MISSING');
      background-color: transparent;
      background-repeat: no-repeat;
      width: 7px;
      overflow: hidden;
      height: 16px;
      vertical-align: top;

      
      -moz-user-select: none;
    }
    .editor .writely-footnote-marker {
      cursor: move;
    }
    .writely-footnote-marker-highlight {
      background-position: -15px 0;
      -moz-user-select: text;
    }
    .writely-footnote-hide-selection ::-moz-selection, .writely-footnote-hide-selection::-moz-selection {
      background: transparent;
    }
    .writely-footnote-hide-selection ::selection, .writely-footnote-hide-selection::selection {
      background: transparent;
    }
    .writely-footnote-hide-selection {
      cursor: move;
    }

    /* Comments */
    .writely-comment-yellow {
      background-color: #ffffd7;
    }
    .writely-comment-orange {
      background-color: #ffe3c0;
    }
    .writely-comment-pink {
      background-color: #ffd7ff;
    }
    .writely-comment-green {
      background-color: #d7ffd7;
    }
    .writely-comment-blue {
      background-color: #d7ffff;
    }
    .writely-comment-purple {
      background-color: #eed7ff;
    }

  


  
  .br_fix span+br:not(:-moz-last-node) {
    
    position:relative;
    
    left: -1ex
    
  }

  
  #cb-p-tgt {
    font-size: 8pt;
    padding: .4em;
    background-color: #ddd;
    color: #333;
  }
  #cb-p-tgt-can {
    text-decoration: underline;
    color: #36c;
    font-weight: bold;
    margin-left: 2em;
  }
  #cb-p-tgt .spin {
    width: 16px;
    height: 16px;
    background: url(//ssl.gstatic.com/docs/clipboard/spin_16o.gif) no-repeat;
  }
}

h6 { font-size: 8pt }
h5 { font-size: 8pt }
h4 { font-size: 10pt }
h3 { font-size: 12pt }
h2 { font-size: 14pt }
h1 { font-size: 18pt }

blockquote {padding: 10px; border: 1px #DDD dashed }

.webkit-indent-blockquote { border: none; }

a img {border: 0}

.pb {
  border-width: 0;
  page-break-after: always;
  /* We don't want this to be resizeable, so enforce a width and height
     using !important */
  height: 1px !important;
  width: 100% !important;
}

.editor .pb {
  border-top: 1px dashed #C0C0C0;
  border-bottom: 1px dashed #C0C0C0;
}

div.google_header, div.google_footer {
  position: relative;
  margin-top: 1em;
  margin-bottom: 1em;
}


/* Table of contents */
.editor div.writely-toc {
  background-color: #f3f3f3;
  border: 1px solid #ccc;
}
.writely-toc > ol {
  padding-left: 3em;
  font-weight: bold;
}
ol.writely-toc-subheading {
  padding-left: 1em;
  font-weight: normal;
}
/* IE6 only */
* html writely-toc ol {
  list-style-position: inside;
}
.writely-toc-none {
  list-style-type: none;
}
.writely-toc-decimal {
  list-style-type: decimal;
}
.writely-toc-upper-alpha {
  list-style-type: upper-alpha;
}
.writely-toc-lower-alpha {
  list-style-type: lower-alpha;
}
.writely-toc-upper-roman {
  list-style-type: upper-roman;
}
.writely-toc-lower-roman {
  list-style-type: lower-roman;
}
.writely-toc-disc {
  list-style-type: disc;
}

/* Ordered lists converted to numbered lists can preserve ordered types, and
   vice versa. This is confusing, so disallow it */
ul[type="i"], ul[type="I"], ul[type="1"], ul[type="a"], ul[type="A"] {
  list-style-type: disc;
}

ol[type="disc"], ol[type="circle"], ol[type="square"] {
  list-style-type: decimal;
}

/* end default css */


  /* default print css */
  
  @media print {
    body {
      padding: 0;
      margin: 0;
    }

    div.google_header, div.google_footer {
      display: block;
      min-height: 0;
      border: none;
    }

    div.google_header {
      flow: static(header);
    }

    /* used to insert page numbers */
    div.google_header::before, div.google_footer::before {
      position: absolute;
      top: 0;
    }

    div.google_footer {
      flow: static(footer);
    }

    /* always consider this element at the start of the doc */
    div#google_footer {
      flow: static(footer, start);
    }

    span.google_pagenumber {
      content: counter(page);
    }

    span.google_pagecount {
      content: counter(pages);
    }

    .endnotes {
      page: endnote;
    }

    /* MLA specifies that endnotes title should be 1" margin from the top of the page. */
    @page endnote {
      margin-top: 1in;
    }

    callout.google_footnote {
      
      display: prince-footnote;
      footnote-style-position: inside;
      /* These styles keep the footnote from taking on the style of the text
         surrounding the footnote marker. They can be overridden in the
         document CSS. */
      color: #000;
      font-family: Georgia;
      font-size: 10.0pt;
      font-weight: normal;
    }

    /* Table of contents */
    #WritelyTableOfContents a::after {
      content: leader('.') target-counter(attr(href), page);
    }

    #WritelyTableOfContents a {
      text-decoration: none;
      color: black;
    }

    /* Comments */
    .writely-comment-yellow {
      background-color: #ffffd7;
    }
    .writely-comment-orange {
      background-color: #ffe3c0;
    }
    .writely-comment-pink {
      background-color: #ffd7ff;
    }
    .writely-comment-green {
      background-color: #d7ffd7;
    }
    .writely-comment-blue {
      background-color: #d7ffff;
    }
    .writely-comment-purple {
      background-color: #eed7ff;
    }
  }

  @page {
    @top {
      content: flow(header);
    }
    @bottom {
      content: flow(footer);
    }
    @footnotes {
      border-top: solid black thin;
      padding-top: 8pt;
    }
  }
  /* end default print css */


/* custom css */


/* end custom css */

/* ui edited css */

body {
  font-family: Georgia;
  
  font-size: 10.0pt;
  line-height: normal;
  background-color: #ffffff;
}
/* end ui edited css */


/* editor CSS */
.editor a:visited {color: #551A8B}
.editor table.zeroBorder {border: 1px dotted gray}
.editor table.zeroBorder td {border: 1px dotted gray}
.editor table.zeroBorder th {border: 1px dotted gray}


.editor div.google_header, .editor div.google_footer {
  border: 2px #DDDDDD dashed;
  position: static;
  width: 100%;
  min-height: 2em;
}

.editor .misspell {background-color: yellow}

.editor .writely-comment {
  font-size: 9pt;
  line-height: 1.4;
  padding: 1px;
  border: 1px dashed #C0C0C0
}


/* end editor CSS */

</style>

  
  <title>NoteShare User Manual</title>

</head>

<body 
    
    >
    
    
    
<div>
  <br>
</div>
<div>
  <div>
    <p style=TEXT-ALIGN:center>
      <a id=NoteShare_Design_Document_2182 name=NoteShare_Design_Document_2182></a><font size=7>NoteShare User Manual</font>
    </p>
    <p style=TEXT-ALIGN:center>
      <br>
    </p>
    <div class=writely-toc id=WritelyTableOfContents toctype=none+none>
      <ol class=writely-toc-none>
        <li>
          <a href=#Adding_Noteshare_to_Facebook_A target=_self>Adding Noteshare to Facebook Apps</a>
        </li>
        <li>
          <a href=#Page_Navigation target=_self>Page Navigation</a>
        </li>
        <li>
          <a href=#User_Homepage_Main_View target=_self>User Homepage / Main View</a>
          <ol class="writely-toc-subheading writely-toc-none" style=MARGIN-LEFT:0pt>
            <li>
              <a href=#Join_A_Course_5706212828802364_086713228918751 target=_self>Join A Course</a>
            </li>
            <li>
              <a href=#Dropping_A_Course_698571103612 target=_self>Drop A Course</a>
            </li>
          </ol>
        </li>
        <li>
          <a href=#Add_Course_021827380045830513 target=_self>Add Course</a>
          <ol class="writely-toc-subheading writely-toc-none" style=MARGIN-LEFT:0pt>
            <li>
              <a href=#Join_Course_4379525107644179_6 target=_self>Join A Course</a>
            </li>
            <li>
              <a href=#Create_A_New_Course_011698395765227243 target=_self>Create A New Course</a>
            </li>
            <li>
              <a href=#Leave_Course_6723113054430868 target=_self>Leave Course</a>
              <ol class="writely-toc-subheading writely-toc-none" style=MARGIN-LEFT:0pt>
                <li>
                  <a href=#Post_to_Class_Wall_22118628632_19351122070690252 target=_self>Post to Class Wall</a>
                </li>
                <li>
                  <a href=#View_members_of_Course_5743287 target=_self>View members of Course</a>
                </li>
              </ol>
            </li>
            <li>
              <a href=#Bulliten_Board_Help_1116845465 target=_self>Bulletin Board Help</a>
              <ol class="writely-toc-subheading writely-toc-none" style=MARGIN-LEFT:0pt>
                <li>
                  <a href=#Read_Bulletin_Board_8652578634_745522573386112 target=_self>Read Bulletin Board</a>
                </li>
                <li>
                  <a href=#Create_a_new_Thread_9015097210 target=_self>Create a new Thread</a>
                </li>
                <li>
                  <a href=#Post_to_Bulletin_Board_6103747_7967282334289972 target=_self>Post to Bulletin Board Thread</a>
                </li>
                <li>
                  <a href=#Remove_Bulletin_Board_Item_880_08947999996604783 target=_self>Remove Bulletin Board Item</a>
                </li>
                <li>
                  <a href=#Rate_a_Bulletin_Board_Post_286_9057774555964077 target=_self>Rate a Bulletin Board Post</a>
                </li>
                <li>
                  <a href=#Report_Abuse_on_a_Bulletin_Boa target=_self>Report Abuse on a Bulletin Board Post</a>
                </li>
              </ol>
            </li>
            <li>
              <a href=#Purpose_of_the_Design_Document_8312716034006976 target=_self>Notes Help</a>
              <ol class="writely-toc-subheading writely-toc-none" style=MARGIN-LEFT:0pt>
                <li>
                  <a href=#Finding_and_Reading_Posted_Not target=_self>Finding and Reading Posted Notes</a>
                </li>
                <li>
                  <a href=#Uploading_Your_Own_Notes_69230 target=_self>Uploading Your Own Notes</a>
                </li>
                <li>
                  <a href=#Deleting_Your_Own_Notes_988273 target=_self>Deleting Your Own Notes</a>
                </li>
                <li>
                  <a href=#Rate_a_set_of_notes_3092849680 target=_self>Rate a set of notes</a>
                </li>
                <li>
                  <a href=#Report_Abuse_on_a_set_of_notes target=_self>Report Abuse on a set of notes</a>
                </li>
              </ol>
            </li>
            <li>
              <a href=#Study_Group_Help_0644341123632 target=_self>Study Group Help</a>
              <ol class="writely-toc-subheading writely-toc-none" style=MARGIN-LEFT:0pt>
                <li>
                  <a href=#Join_Study_Group_7959199467130_7617311465331624 target=_self>Join Study Group</a>
                </li>
                <li>
                  <a href=#Leave_Study_Group_962518504118_15230294634615982 target=_self>Leave Study Group</a>
                </li>
                <li>
                  <a href=#Create_Study_Group_98930464753_2007990769865775 target=_self>Create Study Group</a>
                </li>
                <li>
                  <a href=#Remove_Study_Group_84993357998_9673281492408388 target=_self>Remove Study Group</a>
                </li>
                <li>
                  <a href=#Post_to_Study_Group_Wall_13089_47477245740599594 target=_self>Post to Study Group Wall</a>
                </li>
                <li>
                  <a href=#View_Study_Group_members_80646 target=_self>View Study Group members</a>
                </li>
              </ol>
            </li>
          </ol>
        </li>
      </ol>
    </div>
    <a id=General_Help_9982756240292815__19144628256413654 name=General_Help_9982756240292815__19144628256413654></a><a id=User_Homepage_Main_View_334883388946994 name=User_Homepage_Main_View_334883388946994></a>
    <h1>
      <a id=Adding_Noteshare_to_Facebook_A name=Adding_Noteshare_to_Facebook_A></a>Adding Noteshare to Facebook Apps
    </h1>
    [TODO: add stuff]<br>
    <h1>
      <a id=Page_Navigation name=Page_Navigation></a>Page Navigation
    </h1>
    A page in Noteshare is a frame with in Facebook. All the standard Facebook navigation bars are visible as headers and footers to the Noteshare application. In addition to the Facebook navigation the top of each Noteshare page contains its own header. This header shows the current page as the right-most element before the link to this Help page. All the other links will take you back in the site hierarchy.<br>
    <h1>
      <a id=User_Homepage_Main_View name=User_Homepage_Main_View></a>User Homepage / Main View<font size=5><br>
      </font>
    </h1>
    The User Homepage provides a central location to view all the courses and study groups you are enrolled in. Clicking the title of a course or study group will take you to the page corresponding to it.<br>
  </div>
  <div>
    <h2>
      <a id=Join_A_Course_5706212828802364_086713228918751 name=Join_A_Course_5706212828802364_086713228918751></a>Join A Course
    </h2>
    Clicking the "Join A Course" link in the header will Course Enrollment will take you to the Add Course page where you can add a course to the list of enrollments. You can only view and interact with joined courses, so join as many as you can.<br>
  </div>
  <div>
    <br>
    Joining a new study group requires enrollment in the containing course and can be done through the Course View page.<br>
  </div>
  <div>
    <h2>
      <a id=Dropping_A_Course_698571103612 name=Dropping_A_Course_698571103612></a>Drop A Course
    </h2>
    Any course may be dropped by clicking the [X] next to it and confirming the action.<br>
  </div>
  <div>
    <br>
    Course Homepage<br>
    Study Group Homepage<br>
    <br>
  </div>
  <div>
    <h1>
      <a id=Add_Course_021827380045830513 name=Add_Course_021827380045830513></a>Add Course
    </h1>
    The Add Course page provides a means to join a course from<br>
    <h2>
      <a id=Join_Course_4379525107644179_6 name=Join_Course_4379525107644179_6></a>Join A Course
    </h2>
    To join a course click the drop down boxes and select a University, Department, Course, and Section.&nbsp; The elements must be selected in that order; as the element are selected the next drop down list will highlight and fill with the proper listings. When every thing is selected, click the "Add" button to join the course.<br>
    <h2>
      <a id=Create_A_New_Course_011698395765227243 name=Create_A_New_Course_011698395765227243></a>Create A New Course
    </h2>
    If a selection in any drop down listing is missing choose "Add [Element]..." where Element is one of University, Department, Course, and Section. This will allow you to manually enter the requested course in the provided fields. When all the field have been filled out, click the "Add" button to create and join the course.<br>
    <br>
    If at any time you wish to go back to the drop down listings click the "Revert" button.<br>
  </div>
  <div>
    <a id=Leave_Course_23199600714315094 name=Leave_Course_23199600714315094></a>
    <h2>
      <br>
    </h2>
    <h2>
      <a id=Leave_Course_6723113054430868 name=Leave_Course_6723113054430868></a>Leave Course
    </h2>
    There are two ways to leave a course.&nbsp; From the <a href=http://apps.facebook.com/notesharesep/views/UserHomePage.php id=ap0b title="User Homepage">User Homepage</a> click the small "X" next to a course name to leave that course.&nbsp; The user can also leave a course by clicking the "Drop" button at the top of a Course Homepage.<br>
  </div>
  <div>
    <a href=http://apps.facebook.com/notesharesep/views/UserHomePage.php id=szs7 title="User Homepage"></a>
    <h3>
      <a id=Create_Course_4197882745211069 name=Create_Course_4197882745211069></a><br>
    </h3>
    <a id=Remove_Course_2585785306405858 name=Remove_Course_2585785306405858></a><br>
    <br>
    <h3>
      <a id=Post_to_Class_Wall_22118628632_19351122070690252 name=Post_to_Class_Wall_22118628632_19351122070690252></a>Post to Class Wall
    </h3>
    To post a message on the class wall, the user types their message in the text box at the top of the Course Homepage.&nbsp; Once the message is done, the user clicks the "Share" button to post the message.&nbsp; The five most recent messages are displayed on the Course Homepage.&nbsp; To view older messages, click the "More" link at the top of the Class Wall section.<br>
    <h3>
      <a id=View_members_of_Course_5743287 name=View_members_of_Course_5743287></a>View members of Course
    </h3>
    To view members of a course, the user simply needs to navigate the the Course Homepage for that particular class and scroll towards the bottom of the page.<br>
    <h2>
      <a id=Bulliten_Board_Help_1116845465 name=Bulliten_Board_Help_1116845465></a><font size=5>Bulletin Board Help</font>
    </h2>
    The following help topics apply to both course bulletin boards and study group bulletin boards.<br>
    <h3>
      <a id=Read_Bulletin_Board_8652578634_745522573386112 name=Read_Bulletin_Board_8652578634_745522573386112></a>Read Bulletin Board
    </h3>
    To read posts on the Bulletin Board, the user needs to navigate to either the Course Homepage or the Study Group Homepage and scroll down until they see the Bulletin Board.&nbsp; The Bulletin Board will show the five most recent threads.&nbsp; To view the other threads, click the "View all Topics" link below the shown threads.&nbsp; To read posts, the user just clicks on the title of the thread they wish to read.<br>
    <h3>
      <a id=Create_a_new_Thread_9015097210 name=Create_a_new_Thread_9015097210></a>Create a new Thread
    </h3>
    To create a new thread, the user navigates to either the Course Homepage or Study Group Homepage where they bulletin board is located.&nbsp; The user will scroll down that page and find the bulletin board section and click the "Create New Thread" link.&nbsp; The user will then fill in a Subject for the thread and a message for their post in the body section.&nbsp; To post the thread the user clicks the "Post Thread" button.<br>
    <h3>
      <a id=Post_to_Bulletin_Board_6103747_7967282334289972 name=Post_to_Bulletin_Board_6103747_7967282334289972></a>Post to Bulletin Board Thread<br>
    </h3>
    To post to a bulletin board thread, the user clicks on the subject of the thread to view the messages in that thread.&nbsp; The user then scrolls to the bottom of that page and enters a message in the "Reply" box.&nbsp; To post the user clicks the "Post Reply" button.<br>
    <h3>
      <a id=Remove_Bulletin_Board_Item_880_08947999996604783 name=Remove_Bulletin_Board_Item_880_08947999996604783></a>Remove Bulletin Board Item
    </h3>
    To remove a thread or reply from the bulletin board, the user clicks the "X" link next to the item they wish to remove.<br>
    <h3>
      <a id=Rate_a_Bulletin_Board_Post_286_9057774555964077 name=Rate_a_Bulletin_Board_Post_286_9057774555964077></a>Rate a Bulletin Board Post
    </h3>
    <br>
    <h3>
      <a id=Report_Abuse_on_a_Bulletin_Boa name=Report_Abuse_on_a_Bulletin_Boa></a>Report Abuse on a Bulletin Board Post
    </h3>
    <br>
    <h2>
      <a id=Purpose_of_the_Design_Document_8312716034006976 name=Purpose_of_the_Design_Document_8312716034006976></a><font size=5><b>Notes Help</b></font><br>
    </h2>
    The following help topics apply to both courses and study groups.
    <h3>
      <a id=Finding_and_Reading_Posted_Not name=Finding_and_Reading_Posted_Not></a><b>Finding and Reading Posted Notes</b>
    </h3>
    <p>
      From either the course session or study group homepage, find the section named 'Course Notes' or 'Group Notes'. If you wish to view the notes, simply left click on any of the hyperlink-enabled titles. Only the last five postings will be displayed on this page, but if you would like to view all the postings, click on the link "View all Notes".
    </p>
    <p>
      <br>
    </p>
    <h3>
      <a id=Uploading_Your_Own_Notes_69230 name=Uploading_Your_Own_Notes_69230></a><b>Uploading Your Own Notes</b>
    </h3>
    From the course session or study group homepage, click on the link labeled "Add New Note Set" or from the expanded notes page, click on '"Add New Note Set". Enter a subject (limit of 45 characters), body (limit of 100 characters), and path to the uploaded note file. The note file must be under 5 MB and be in either: PS, ZIP, DVI, TXT, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPEG, or PNG format. To upload the file, click the "Post Note"' button, otherwise to cancel click "Cancel".
    <p>
      <br>
    </p>
    <h3>
      <a id=Deleting_Your_Own_Notes_988273 name=Deleting_Your_Own_Notes_988273></a><b>Deleting Your Own Notes</b>
    </h3>
    From the course session, study group homepage, or expanded notes page click on the "X" link on the same line as the note header. Next, a pop up window will appear, click "OK" to finalize deleting the note.<br>
    <br>
    <h3>
      <a id=Rate_a_set_of_notes_3092849680 name=Rate_a_set_of_notes_3092849680></a>Rate a set of notes
    </h3>
    <br>
    <h3>
      <a id=Report_Abuse_on_a_set_of_notes name=Report_Abuse_on_a_set_of_notes></a>Report Abuse on a set of notes
    </h3>
    <br>
    <h2>
      <a id=Study_Group_Help_0644341123632 name=Study_Group_Help_0644341123632></a><font size=5>Study Group Help</font>
    </h2>
    <h3>
      <a id=Join_Study_Group_7959199467130_7617311465331624 name=Join_Study_Group_7959199467130_7617311465331624></a>Join Study Group
    </h3>
    To join a study group, the user goes to the class homepage and scrolls to the bottom of the page to find the list of available study groups.&nbsp; The user simply clicks the "Join" link next to a study group to join.<br>
    <h3>
      <a id=Leave_Study_Group_962518504118_15230294634615982 name=Leave_Study_Group_962518504118_15230294634615982></a>Leave Study Group
    </h3>
    To Leave a Study Group, the user navigates to either the class homepage the study group is a part of or to the study group homepage.&nbsp; From the class homepage, scroll to the bottom of the page where the study groups are listed and click the "X" link next to the study group you wish to leave.&nbsp; From the study group homepage, click the "Drop" button at the top of the page.<br>
    <h3>
      <a id=Create_Study_Group_98930464753_2007990769865775 name=Create_Study_Group_98930464753_2007990769865775></a>Create Study Group
    </h3>
    <br>
    <h3>
      <a id=Remove_Study_Group_84993357998_9673281492408388 name=Remove_Study_Group_84993357998_9673281492408388></a>Remove Study Group
    </h3>
    <br>
    <h3>
      <a id=Post_to_Study_Group_Wall_13089_47477245740599594 name=Post_to_Study_Group_Wall_13089_47477245740599594></a>Post to Study Group Wall
    </h3>
    See <a href=http://docs.google.com/Doc?docid=0AXJEoX3unDSCYWprNzNtbmhtOXZ4XzMzNm5tcXgzczh3&amp;hl=en#Post_to_Class_Wall_22118628632_10541113166911376 target=_self>Post to Class Wall</a>.&nbsp; Replace Course homepage with study group homepage.<br>
  </div>
</div>
<h3>
  <a id=View_Study_Group_members_80646 name=View_Study_Group_members_80646></a>View Study Group members
</h3>
Navigate to the study group homepage and scroll to the bottom of the page to see the members of that study group.<br></body>
</html>
