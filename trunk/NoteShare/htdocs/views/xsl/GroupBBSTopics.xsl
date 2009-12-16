<?xml version="1.0" encoding="ISO-8859-1"?>

<xsl:stylesheet version="1.0"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
xmlns:fb="http://www.facebook.com/2008/fbml" 
>

<xsl:template match="/">
	<xsl:apply-templates/>
</xsl:template>

<xsl:template match="StudyGroupBBSTopics">
  <xsl:variable name="viewUserId"><xsl:value-of select="UserId" /></xsl:variable>
  <xsl:variable name="sessionId"><xsl:value-of select="SessionId" /></xsl:variable>
  <table class="GroupBBS">
    <xsl:for-each select="StudyGroupBBSTopic">
    <tr>
      <td class="GroupBBSTopic fbFont">
        <a target="_top">
          <xsl:attribute name="href">
            http://apps.facebook.com/notesharesep/views/GroupBBS.php?ns_session=<xsl:value-of select="$sessionId" />&amp;parentId=<xsl:value-of select="@Id" />
          </xsl:attribute>
          <xsl:value-of select="." />
        </a>
        <br />
        Created on <xsl:value-of select="@PostDate" />
      </td>
      <td class="fbFont">
        <xsl:if test="$viewUserId=@UserId">
          [
          <a target="_top">
            <xsl:attribute name="href">
              http://apps.facebook.com/notesharesep/controllers/GroupBBSTopics.php?ns_session=<xsl:value-of select="$sessionId" />&amp;nsStudyGroup=<xsl:value-of select="@StudyGroupId" />&amp;postId=<xsl:value-of select="@Id" />&amp;funct=DELETEBBS
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

</xsl:stylesheet>
