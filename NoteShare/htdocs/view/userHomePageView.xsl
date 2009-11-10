<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:key name="session-by-uni" match="SessionUserItem" use="@University_Name" />

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="UserSessionList">
	<xsl:for-each select="UserSessionItem[count(. | key('session-by-uni', @University_Name)[1]) = 1]">
		<div>
			<!--<xsl:sort select="@University_Name" />-->
	   		<h3><xsl:value-of select="@University_Name" /></h3>
	   		<ul>
		   		<xsl:for-each select="key('session-by-uni', @University_Name)">
		   			<li>
			   			<a target="_top">
							<xsl:attribute name="href">
								http://apps.facebook.com/notesharesep/view/CoursePageView.php?session=<xsl:value-of select="@Id" />
							</xsl:attribute>
							<xsl:value-of select="." />
						</a>
						[<a target="_top" onclick="Really? Drop the course?">
					    	<xsl:attribute name="href">
				      			http://apps.facebook.com/notesharesep/view/DropCourse.php?session=<xsl:value-of select="@Id" />
				     		</xsl:attribute>
							X
						</a>]
					</li>
		   		</xsl:for-each>
		   	</ul>
		</div>
	</xsl:for-each>
</xsl:template>

<!-- This defines the transformation of the XML session tag.
	produces a li of buttons (I know ugly, CSS to the rescue)  
	to the course page with a short description with a drop button 
<xsl:template match="UserSessionList">
	<ul>  
		<xsl:for-each select="SessionUserItem">
					<li>
						 <xsl:attribute name="id"><xsl:value-of select="@Id"/></xsl:attribute>
  						<a target="_top">
							<xsl:attribute name="href">
								http://apps.facebook.com/notesharesep/view/CoursePageView.php?noteshare_session=<xsl:value-of select="@Id" />
							</xsl:attribute>
							<xsl:value-of select="." />
						</a>				
							<form action="http://apps.facebook.com/notesharesep/view/CoursePageView.php" method="GET" target="_top">
							<button class="course" name="session">
								<xsl:attribute name="value"><xsl:value-of select="@Id"/></xsl:attribute>
								<xsl:value-of select="." />
							</button>
							</form>
						
				      [
							<a target="_top">
					    	<xsl:attribute name="href">
				      		http://apps.facebook.com/notesharesep/view/DropCourse.php?session=<xsl:value-of select="@Id" />
				     		</xsl:attribute>
				      	<xsl:attribute name="onclick">
				     			return confirm('Really? Drop the course?');
				      	</xsl:attribute>
								X
				      </a>
				      ]
				  </li>
		</xsl:for-each>
	</ul>
</xsl:template>
-->
<!-- Define other templates for other element types (eg user, note...)-->
<!--
  <xsl:value-of select="@course"/>: <xsl:value-of select="."/>, 
	<xsl:value-of select="@department"/>, <xsl:value-of select="@university"/>

        <form action="http://apps.facebook.com/notesharesep/view/DropCourse.php" method="GET" target="_top">
          <button class="drop" name="session" onclick="return confirm('Really? Drop the course?');">
          <xsl:attribute name="value"><xsl:value-of select="@Id"/></xsl:attribute>Drop</button>
        </form>

				<input type="submit" class="editorkit_button action">
          <xsl:attribute name="name">session</xsl:attribute>
          <xsl:attribute name="value">Value</xsl:attribute>
        </input>

-->
</xsl:stylesheet>
