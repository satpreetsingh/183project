<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="SessionMetaData">
  <table class="sessionMetaData">
    <tr><td class="fbFont sessionName"><xsl:value-of select='.' /></td></tr>
	  <tr><td class="fbFont sessionDescr">Description:<p><xsl:value-of select="@Desc"/></p></td></tr>
  </table>
</xsl:template>

<xsl:template match="sessionWallPosts">
	<fb:serverfbml>
		<script type="text/fbml">
			<fb:wall>
				<xsl:for-each select="post">
					<fb:wallpost>
						<xsl:attribute name="uid"><xsl:value-of select="@user" /></xsl:attribute>
						<xsl:attribute name="t"><xsl:value-of select="@time" /></xsl:attribute>
						<xsl:value-of select="." />
					</fb:wallpost>
				</xsl:for-each>
			</fb:wall>
		</script>
	</fb:serverfbml>
</xsl:template>

<xsl:template match="SessionUserList">
	<div>
		<xsl:for-each select="SessionUserItem">
			<xsl:sort select="@friend" order="descending" />
			<div>
				<xsl:attribute name="class">
					<xsl:choose>
						<xsl:when test="@friend='True'">friend</xsl:when>
						<xsl:otherwise>notFriend</xsl:otherwise>
					</xsl:choose>
				</xsl:attribute>
				<fb:profile-pic linked="true">
					<xsl:attribute name="uid"><xsl:value-of select="@id"/></xsl:attribute>
				</fb:profile-pic>
				<fb:name>
					<xsl:attribute name="uid"><xsl:value-of select="@id"/></xsl:attribute>
				</fb:name>
				</div>
		</xsl:for-each> 
	</div>
</xsl:template>

<xsl:template match="SessionBBSTopics">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <table class="sessionBBS">
    <xsl:for-each select="SessionBBSTopic">
    <tr>
      <td class="sessionBBSTopic fbFont">
        <a target="_top">
          <xsl:attribute name="href">
            http://apps.facebook.com/notesharesep/views/SessionBBS.php?ns_session=<xsl:value-of select="@SessionId" />&amp;parentId=<xsl:value-of select="@Id" />
          </xsl:attribute>
          <xsl:value-of select="." />
        </a>
        <br />
        Created on <xsl:value-of select="@PostDate" />
      </td>
      <td class="fbFont">
        <br />
        <xsl:if test="$viewUserId=@UserId">
          [
          <a target="_top">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/CourseHomePage.php?ns_session=<xsl:value-of select="@SessionId" />&amp;parentId=<xsl:value-of select="@Id" />&amp;funct=DELETEBBS
            </xsl:attribute>
            X
          </a>
          ]
        </xsl:if>
      </td>
    </tr>
    </xsl:for-each>
  </table>
</xsl:template>

<!--
<getSessionNote>
  <getSessionNote User_ID="" Header="" Body="" Original_File_Name="" File_Size="1"></getSessionNote>
-->
<xsl:template match="getSessionNotes">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <table class="sessionNotes">
    <xsl:for-each select="getSessionNote">
    <tr>
      <td class="sessionNote fbFont">
        <a target="_top">
          <xsl:attribute name="href">
            <xsl:value-of select="." />
          </xsl:attribute>        
          <xsl:value-of select="@Header" />
        </a>
      </td>
      <td class="fbFont right">
        <br />
        <xsl:if test="$viewUserId=@User_ID">
          [
          <a target="_top" onclick="return confirm('Really? Delete these notes?');">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/CourseHomePage.php?ns_session=<xsl:value-of select="@SessionId" />&amp;noteId=<xsl:value-of select="@Id" />&amp;funct=DELETENOTE
            </xsl:attribute>
            X
          </a>
          ]
        </xsl:if>
      </td>
    </tr>
    <tr>
      <td class="sessionNote fbFont">
        <xsl:value-of select="@Body" />
      </td>
    </tr>
    </xsl:for-each>
  </table>
</xsl:template>
</xsl:stylesheet>